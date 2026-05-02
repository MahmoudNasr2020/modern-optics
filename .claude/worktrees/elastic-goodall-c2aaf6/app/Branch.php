<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'address',
        'city',
        'phone',
        'email',
        'manager_name',
        'description',
        'is_active',
        'is_main',
        'opening_time',
        'closing_time',
        'total_sales',
        'total_invoices',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_main' => 'boolean',
        'total_sales' => 'decimal:2',
        'total_invoices' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($branch) {
            if (empty($branch->code)) {
                $branch->code = self::generateCode();
            }
        });
    }

    public static function generateCode()
    {
        $lastBranch = self::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastBranch ? $lastBranch->id + 1 : 1;
        return 'BR-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * ====================================================
     * RELATIONSHIPS
     * ====================================================
     */

    // Stock (all items - products + lenses)
    public function stock()
    {
        return $this->hasMany(BranchStock::class);
    }

    // ✅ NEW: Products stock only
    public function productsStock()
    {
        return $this->hasMany(BranchStock::class)
            ->where('stockable_type', 'App\\Product');
    }

    // ✅ NEW: Lenses stock only
    public function lensesStock()
    {
        return $this->hasMany(BranchStock::class)
            ->where('stockable_type', 'App\\glassLense');
    }

    // ✅ NEW: Lenses in this branch
    public function lenses()
    {
        return $this->hasMany(glassLense::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function transfersTo()
    {
        return $this->hasMany(StockTransfer::class, 'to_branch_id');
    }

    public function transfersFrom()
    {
        return $this->hasMany(StockTransfer::class, 'from_branch_id');
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    /**
     * ====================================================
     * ACCESSORS
     * ====================================================
     */

    public function getFullNameAttribute()
    {
        return $this->name_ar ?: $this->name;
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_active ?
            '<span class="badge bg-success">Active</span>' :
            '<span class="badge bg-danger">Inactive</span>';
    }

    public function getTotalStockAttribute()
    {
        return $this->stock()->sum('quantity');
    }

    public function getTotalProductsAttribute()
    {
        return $this->stock()->count();
    }

    // ✅ NEW: Total products count
    public function getTotalProductsOnlyAttribute()
    {
        return $this->productsStock()->count();
    }

    // ✅ NEW: Total lenses count
    public function getTotalLensesAttribute()
    {
        return $this->lensesStock()->count();
    }

    /**
     * ====================================================
     * METHODS - PRODUCTS
     * ====================================================
     */

    public function getProductStock($productId)
    {
        return $this->stock()
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $productId)
            ->first();
    }

    public function hasProductStock($productId, $quantity = 1)
    {
        $stock = $this->getProductStock($productId);
        return $stock && $stock->available_quantity >= $quantity;
    }

    public function addProductStock($productId, $quantity, $cost = null)
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        $stock = $this->stock()->firstOrCreate(
            [
                'stockable_type' => 'App\\Product',
                'stockable_id' => $productId,
                'product_id' => $productId, // For backward compatibility
            ],
            [
                'quantity' => 0,
                'min_quantity' => 5,
                'max_quantity' => 100
            ]
        );

        $stock->addQuantity($quantity, $cost);

        return $stock;
    }

    public function reduceProductStock($productId, $quantity)
    {
        $stock = $this->getProductStock($productId);

        if (!$stock || $stock->available_quantity < $quantity) {
            throw new \Exception('Insufficient product stock');
        }

        $stock->reduceQuantity($quantity);

        return $stock;
    }

    /**
     * ====================================================
     * METHODS - LENSES ✅ NEW
     * ====================================================
     */

    public function getLensStock($lensId)
    {
        return $this->stock()
            ->where('stockable_type', 'App\\glassLense')
            ->where('stockable_id', $lensId)
            ->first();
    }

    public function hasLensStock($lensId, $quantity = 1)
    {
        $stock = $this->getLensStock($lensId);
        return $stock && $stock->available_quantity >= $quantity;
    }

    public function addLensStock($lensId, $quantity, $cost = null)
    {
        $lens = glassLense::find($lensId);

        if (!$lens) {
            throw new \Exception('Lens not found');
        }

        $stock = $this->stock()->firstOrCreate(
            [
                'stockable_type' => 'App\\glassLense',
                'stockable_id' => $lensId,
            ],
            [
                'quantity' => 0,
                'min_quantity' => 2,
                'max_quantity' => 50
            ]
        );

        $stock->addQuantity($quantity, $cost);

        return $stock;
    }

    public function reduceLensStock($lensId, $quantity)
    {
        $stock = $this->getLensStock($lensId);

        if (!$stock || $stock->available_quantity < $quantity) {
            throw new \Exception('Insufficient lens stock');
        }

        $stock->reduceQuantity($quantity);

        return $stock;
    }

    /**
     * ====================================================
     * GENERIC METHODS (Works with both)
     * ====================================================
     */

    // ✅ NEW: Get stock for any item (product or lens)
    public function getItemStock($type, $id)
    {
        $stockableType = $type === 'product' ? 'App\\Product' : 'App\\glassLense';

        return $this->stock()
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $id)
            ->first();
    }

    // ✅ NEW: Add stock for any item
    public function addItemStock($type, $id, $quantity, $cost = null)
    {
        if ($type === 'product') {
            return $this->addProductStock($id, $quantity, $cost);
        } else {
            return $this->addLensStock($id, $quantity, $cost);
        }
    }

    // ✅ NEW: Reduce stock for any item
    public function reduceItemStock($type, $id, $quantity)
    {
        if ($type === 'product') {
            return $this->reduceProductStock($id, $quantity);
        } else {
            return $this->reduceLensStock($id, $quantity);
        }
    }

    public function getLowStockProducts()
    {
        return $this->stock()
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->with('stockable')
            ->get();
    }

    public function getAvailableProducts()
    {
        return $this->stock()
            ->where('quantity', '>', 0)
            ->with('stockable')
            ->get();
    }

    public function cashierTransactions()
    {
        return $this->hasManyThrough(
            CashierTransaction::class,
            Invoice::class,
            'branch_id',      // Foreign key on invoices table
            'invoice_id',     // Foreign key on cashier_transactions table
            'id',             // Local key on branches table
            'id'              // Local key on invoices table
        );
    }
}
