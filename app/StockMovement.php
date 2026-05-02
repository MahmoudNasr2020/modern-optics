<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'branch_id',
        'product_id',
        'stockable_type',
        'stockable_id',
        'type',
        'quantity',
        'cost',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
        'balance_before',
        'balance_after',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost' => 'decimal:2',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
    ];

    /**
     * ====================================================
     * RELATIONSHIPS
     * ====================================================
     */

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ✅ NEW: Polymorphic relationship (Product or glassLense)
    public function stockable()
    {
        return $this->morphTo();
    }

    // Keep old relationship for backward compatibility
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    public function scopeIncoming($query)
    {
        return $query->whereIn('type', ['in', 'transfer_in', 'return']);
    }

    public function scopeOutgoing($query)
    {
        return $query->whereIn('type', ['out', 'transfer_out', 'sale','reserve']);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Old scope - uses product_id
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // ✅ NEW: For specific stockable item
    public function scopeForStockable($query, $type, $id)
    {
        return $query->where('stockable_type', $type)
            ->where('stockable_id', $id);
    }

    // ✅ NEW: Products only
    public function scopeProducts($query)
    {
        return $query->where('stockable_type', 'App\\Product');
    }

    // ✅ NEW: Lenses only
    public function scopeLenses($query)
    {
        return $query->where('stockable_type', 'App\\glassLense');
    }

    /**
     * ====================================================
     * ACCESSORS
     * ====================================================
     */

    public function getTypeInfoAttribute()
    {
        $types = [
            'in' => ['label' => 'Stock In', 'icon' => 'arrow-down', 'class' => 'success'],
            'out' => ['label' => 'Stock Out', 'icon' => 'arrow-up', 'class' => 'danger'],
            'adjustment' => ['label' => 'Adjustment', 'icon' => 'edit', 'class' => 'warning'],
            'transfer_in' => ['label' => 'Transfer In', 'icon' => 'bi bi-arrow-down-circle', 'class' => 'info'],
            'transfer_out' => ['label' => 'Transfer Out', 'icon' => 'bi bi-arrow-up-circle', 'class' => 'primary'],
            'sale' => ['label' => 'Sale', 'icon' => 'cart', 'class' => 'success'],
            'return' => ['label' => 'Return', 'icon' => 'undo', 'class' => 'warning'],
            'reserve' => ['label' => 'Reserve', 'icon'  => 'bookmark-check', 'class' => 'info'],

        ];

        return $types[$this->type] ?? ['label' => $this->type, 'icon' => 'question', 'class' => 'default'];
    }

    public function getIsIncomingAttribute()
    {
        return in_array($this->type, ['in', 'transfer_in', 'return']);
    }

    public function getIsOutgoingAttribute()
    {
        return in_array($this->type, ['out', 'transfer_out', 'sale']);
    }

    // ✅ NEW: Get item description
    public function getItemDescriptionAttribute()
    {
        if ($this->stockable) {
            return $this->stockable->description ?? 'N/A';
        }

        // Fallback to old product
        if ($this->product) {
            return $this->product->description;
        }

        return 'Unknown Item';
    }

    // ✅ NEW: Get item type
    public function getItemTypeAttribute()
    {
        if ($this->stockable_type === 'App\\Product') {
            return 'Product';
        } elseif ($this->stockable_type === 'App\\glassLense') {
            return 'Lens';
        }

        return 'Unknown';
    }

    /**
     * ====================================================
     * STATIC METHODS - Create Movements
     * ====================================================
     */


    public static function createForProduct($branchId, $productId, $type, $quantity, $userId, $data = [])
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        $stock = BranchStock::where('branch_id', $branchId)
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $productId)
            ->first();

        // Use caller-supplied balance if provided (avoids reading post-update value)
        if (array_key_exists('balance_before', $data)) {
            $balanceBefore = (int) $data['balance_before'];
        } else {
            $balanceBefore = $stock ? $stock->quantity : 0;
        }
        $balanceAfter = $balanceBefore + (in_array($type, ['in', 'transfer_in', 'return']) ? abs($quantity) : -abs($quantity));

        return self::create([
            'branch_id' => $branchId,
            'product_id' => $productId,  // For backward compatibility
            'stockable_type' => 'App\\Product',
            'stockable_id' => $productId,
            'type' => $type,
            'quantity' => abs($quantity),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'user_id' => $userId,
            'cost' => $data['cost'] ?? null,
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
        ]);
    }


    public static function createForLens($branchId, $lensId, $type, $quantity, $userId, $data = [])
    {
        $lens = glassLense::find($lensId);

        if (!$lens) {
            throw new \Exception('Lens not found');
        }

        $stock = BranchStock::where('branch_id', $branchId)
            ->where('stockable_type', 'App\\glassLense')
            ->where('stockable_id', $lensId)
            ->first();

        // Use caller-supplied balance if provided (avoids reading post-update value)
        if (array_key_exists('balance_before', $data)) {
            $balanceBefore = (int) $data['balance_before'];
        } else {
            $balanceBefore = $stock ? $stock->quantity : 0;
        }
        $balanceAfter = $balanceBefore + (in_array($type, ['in', 'transfer_in', 'return']) ? abs($quantity) : -abs($quantity));

        return self::create([
            'branch_id' => $branchId,
            'stockable_type' => 'App\\glassLense',
            'stockable_id' => $lensId,
            'type' => $type,
            'quantity' => abs($quantity),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'user_id' => $userId,
            'cost' => $data['cost'] ?? null,
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
        ]);
    }

    // ✅ NEW: Generic create method
    public static function createForItem($itemType, $branchId, $itemId, $type, $quantity, $userId, $data = [])
    {
        if ($itemType === 'product') {
            return self::createForProduct($branchId, $itemId, $type, $quantity, $userId, $data);
        } else {
            return self::createForLens($branchId, $itemId, $type, $quantity, $userId, $data);
        }
    }
}
