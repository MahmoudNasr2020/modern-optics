<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class glassLense extends Model
{
    protected $table = 'glass_lenses';

    protected $fillable = [
        'product_id',
        'branch_id',
        'description',
        'brand',
        'frame_type',
        'lense_type',
        'brand',
        'production',
        'index',
        'life_style',
        'customer_activity',
        'lense_tech',
        'retail_price',
        'amount',  // This is the quantity for lenses
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'amount' => 'integer',
    ];

    /**
     * ====================================================
     * RELATIONSHIPS
     * ====================================================
     */

    // ✅ Branch relationship
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ✅ NEW: Polymorphic - this lens can be in BranchStock
    public function branchStocks()
    {
        return $this->morphMany(BranchStock::class, 'stockable');
    }

    // ✅ NEW: Get stock for specific branch
    public function stockInBranch($branchId)
    {
        return $this->branchStocks()->where('branch_id', $branchId)->first();
    }

    // ✅ NEW: Check if has stock in branch
    public function hasStockInBranch($branchId, $quantity = 1)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock && $stock->available_quantity >= $quantity;
    }

    // ✅ NEW: Get available quantity in branch
    public function getAvailableQuantityInBranch($branchId)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock ? $stock->available_quantity : 0;
    }

    /**
     * ====================================================
     * ACCESSORS
     * ====================================================
     */

    // Total stock across all branches (from branchStocks)
    public function getTotalStockAttribute()
    {
        return $this->branchStocks()->sum('quantity');
    }

    // Available branches (where stock > 0)
    public function getAvailableBranchesAttribute()
    {
        return $this->branchStocks()
            ->where('quantity', '>', 0)
            ->with('branch')
            ->get()
            ->pluck('branch');
    }

    // Full name/description
    public function getFullNameAttribute()
    {
        return $this->description . ' (' . $this->brand . ' - ' . $this->index . ')';
    }

    //get stock for product
    public function getQuantityInBranch($branchId)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock ? $stock->quantity : 0;
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    // Active lenses (amount > 0)
    public function scopeActive($query)
    {
        return $query->where('amount', '>', 0);
    }

    // Available in specific branch
    public function scopeAvailableInBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId)
            ->where('amount', '>', 0);
    }

    // In stock
    public function scopeInStock($query)
    {
        return $query->where('amount', '>', 0);
    }

    // By brand
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    // By index
    public function scopeByIndex($query, $index)
    {
        return $query->where('index', $index);
    }

    // Search
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('product_id', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('brand', 'LIKE', "%{$term}%");
        });
    }

    /**
     * ====================================================
     * METHODS
     * ====================================================
     */

    // ✅ Reduce amount (for sales)
    public function reduceAmount($quantity)
    {
        if ($this->amount < $quantity) {
            throw new \Exception('Insufficient lens quantity');
        }

        $this->decrement('amount', $quantity);
        return $this;
    }

    // ✅ Add amount (for restocking)
    public function addAmount($quantity)
    {
        $this->increment('amount', $quantity);
        return $this;
    }

    // ✅ Get with branch stock info
    public static function getWithBranchStock($branchId)
    {
        return self::with(['branchStocks' => function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        }])
            ->whereHas('branchStocks', function($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                    ->where('quantity', '>', 0);
            });
    }


    /**
     * ✅ حجز كمية في فرع معين
     */
    public function reserveInBranch($branchId, $quantity)
    {
        $stock = $this->stockInBranch($branchId);

        if (!$stock || $stock->available_quantity < $quantity) {
            throw new \Exception('Insufficient stock to reserve');
        }

        $stock->reserveQuantity($quantity);
        return $stock;
    }

    /**
     * ✅ إلغاء حجز كمية في فرع معين
     */
    public function unreserveInBranch($branchId, $quantity)
    {
        $stock = $this->stockInBranch($branchId);

        if ($stock) {
            $stock->unreserveQuantity($quantity);
        }

        return $stock;
    }

    /**
     * ✅ تقليل المخزون في فرع معين
     */
    public function reduceStockInBranch($branchId, $quantity)
    {
        $stock = $this->stockInBranch($branchId);

        // ✅ Check actual quantity (not available)
        if (!$stock || $stock->quantity < $quantity) {
            throw new \Exception("Insufficient stock for lens {$this->product_id} in branch {$branchId}. Have: " . ($stock ? $stock->quantity : 0) . ", need: {$quantity}");
        }

        $stock->reduceQuantity($quantity);
        return $stock;
    }


    /**
     * ✅ زيادة المخزون في فرع معين
     */
    public function addStockInBranch($branchId, $quantity, $cost = null)
    {
        $stock = $this->stockInBranch($branchId);

        if (!$stock) {
            throw new \Exception('Stock record not found for this branch');
        }

        $stock->addQuantity($quantity, $cost);
        return $stock;
    }
}
