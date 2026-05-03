<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //use SoftDeletes;

    protected $table = 'products';

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'tax' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * ====================================================
     * RELATIONSHIPS - العلاقات
     * ====================================================
     */

    /**
     * ✅ NEW: Polymorphic - المخزون في الفروع
     * Product can be in BranchStock as stockable
     */
    public function branchStocks()
    {
        return $this->morphMany(BranchStock::class, 'stockable');
    }

    /**
     * جلب مخزون المنتج في فرع معين
     */
    public function stockInBranch($branchId)
    {
        return $this->branchStocks()
            ->where('branch_id', $branchId)
            ->first();
    }

    /**
     * الفروع اللي فيها المنتج ده
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_stock', 'stockable_id', 'branch_id')
            ->wherePivot('stockable_type', 'App\\Product')
            ->withPivot(['quantity', 'min_quantity', 'max_quantity', 'reserved_quantity'])
            ->withTimestamps();
    }

    /**
     * الكاتيجوري
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * البراند
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * الموديل
     */
    public function model()
    {
        return $this->belongsTo(glassModel::class, 'model_id');
    }

    /**
     * بنود الفواتير
     */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItems::class, 'product_id', 'product_id');
    }

    /**
     * ✅ NEW: Polymorphic - حركات المخزون
     */
    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }

    /**
     * ✅ NEW: Polymorphic - تحويلات المخزون
     */
    public function stockTransfers()
    {
        return $this->morphMany(StockTransfer::class, 'stockable');
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    /**
     * المنتجات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * المنتجات المتاحة في فرع معين
     */
    public function scopeAvailableInBranch($query, $branchId)
    {
        return $query->whereHas('branchStocks', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId)
                ->where('quantity', '>', 0);
        });
    }

    /**
     * المنتجات اللي عندها مخزون
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('branchStocks', function ($q) {
            $q->where('quantity', '>', 0);
        });
    }

    /**
     * فلتر حسب الكاتيجوري
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * فلتر حسب البراند
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    /**
     * بحث في الوصف
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('product_id', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * ====================================================
     * ACCESSORS & MUTATORS
     * ====================================================
     */

    /**
     * الاسم الكامل للمنتج
     */
    public function getFullNameAttribute()
    {
        return "{$this->product_id} - {$this->description}";
    }

    /**
     * السعر بعد الضريبة
     */
    public function getPriceWithTaxAttribute()
    {
        return $this->price + ($this->price * $this->tax / 100);
    }

    /**
     * السعر الصافي (قبل الضريبة)
     */
    public function getNetPriceAttribute()
    {
        return $this->price - ($this->price * $this->tax / 100);
    }

    /**
     * إجمالي المخزون في كل الفروع
     */
    public function getTotalStockAttribute()
    {
        return $this->branchStocks()->sum('quantity');
    }

    /**
     * ✅ NEW: المخزون المتاح (بعد الحجز)
     */
    public function getTotalAvailableStockAttribute()
    {
        return $this->branchStocks()->get()->sum('available_quantity');
    }

    /**
     * ✅ NEW: المخزون المحجوز
     */
    public function getTotalReservedStockAttribute()
    {
        return $this->branchStocks()->sum('reserved_quantity');
    }

    /**
     * ====================================================
     * METHODS
     * ====================================================
     */

    /**
     * التحقق من توفر الكمية في فرع معين
     */
    public function hasStockInBranch($branchId, $quantity = 1)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock && $stock->available_quantity >= $quantity;
    }

    /**
     * جلب الكمية المتاحة في فرع
     */
    public function getQuantityInBranch($branchId)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock ? $stock->quantity : 0;
    }

    /**
     * جلب الكمية المتاحة في فرع
     */
    public function getAvailableQuantityInBranch($branchId)
    {
        $stock = $this->stockInBranch($branchId);
        return $stock ? $stock->available_quantity : 0;
    }

    /**
     * جلب معلومات المنتج مع المخزون من فرع معين
     */
    public function getWithBranchStock($branchId)
    {
        $stock = $this->stockInBranch($branchId);

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'model_id' => $this->model_id,
            'price' => $this->price,
            'retail_price' => $this->retail_price,
            'tax' => $this->tax,
            'net_price' => $this->net_price,
            'stock' => $stock ? $stock->quantity : 0,
            'available_quantity' => $stock ? $stock->available_quantity : 0,
            'reserved_quantity' => $stock ? $stock->reserved_quantity : 0,
            'stock_status' => $stock ? $stock->stock_status : null,
        ];
    }

    /**
     * جلب الفروع اللي فيها المنتج متوفر
     */
    public function getAvailableBranches()
    {
        return $this->branchStocks()
            ->where('quantity', '>', 0)
            ->with('branch')
            ->get()
            ->pluck('branch');
    }

    /**
     * ✅ NEW: جلب المنتجات مع المخزون من فرع معين
     * Static method to get products with stock
     */
    public static function getWithBranchStockQuery($branchId)
    {
        return self::with(['branchStocks' => function($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        }])
            ->whereHas('branchStocks', function($q) use ($branchId) {
                $q->where('branch_id', $branchId)->where('quantity', '>', 0);
            });
    }

    /**
     * ✅ NEW: حجز كمية في فرع معين
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
     * ✅ NEW: إلغاء حجز كمية في فرع معين
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
     * ✅ NEW: تقليل المخزون في فرع معين
     */
    public function reduceStockInBranch($branchId, $quantity)
    {
        $stock = $this->stockInBranch($branchId);

        // ✅ Check actual quantity (not available)
        if (!$stock || $stock->quantity < $quantity) {
            throw new \Exception("Insufficient stock for {$this->product_id} in branch {$branchId}. Have: " . ($stock ? $stock->quantity : 0) . ", need: {$quantity}");
        }

        $stock->reduceQuantity($quantity);
        return $stock;
    }

    /**
     * ✅ NEW: زيادة المخزون في فرع معين
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

    // ============================================================
    //  SAFE DELETION — 3-tier logic
    // ============================================================

    /**
     * Smart deletion with full safety checks.
     *
     * Returns an array:
     *   [
     *     'action'   => 'blocked' | 'archived' | 'deleted',
     *     'message'  => string (Arabic),
     *     'branches' => Collection (only when blocked),
     *   ]
     *
     * Tier 1 — BLOCKED  : any branch has quantity > 0 or reserved_quantity > 0
     * Tier 2 — ARCHIVED : no stock, but linked to invoice history → set is_active = false
     * Tier 3 — DELETED  : no stock, no invoices → hard delete + orphan cleanup
     */
    public function safeDelete(): array
    {
        // ── Tier 1: Block if any branch still holds stock ──────────
        $blockedBranches = $this->branchStocks()
            ->where(function ($q) {
                $q->where('quantity', '>', 0)
                  ->orWhere('reserved_quantity', '>', 0);
            })
            ->with('branch')
            ->get();

        if ($blockedBranches->isNotEmpty()) {
            $list = $blockedBranches->map(function ($s) {
                $parts = [];
                if ($s->quantity > 0)          $parts[] = 'كمية: ' . $s->quantity;
                if ($s->reserved_quantity > 0) $parts[] = 'محجوز: ' . $s->reserved_quantity;
                $branchName = optional($s->branch)->name ?? 'فرع غير معروف';
                return $branchName . ' (' . implode('، ', $parts) . ')';
            })->implode(' — ');

            return [
                'action'   => 'blocked',
                'message'  => 'لا يمكن الحذف — يوجد مخزون في: ' . $list,
                'branches' => $blockedBranches,
            ];
        }

        // ── Tier 2: Archive if the product appears on any invoice ──
        $invoiceCount = InvoiceItems::where('product_id', $this->product_id)->count();

        if ($invoiceCount > 0) {
            // Deactivate — preserves all history
            $this->update(['is_active' => false]);
            // Remove any orphaned zero-qty branch-stock rows
            $this->branchStocks()
                 ->where('quantity', '<=', 0)
                 ->where('reserved_quantity', '<=', 0)
                 ->delete();

            return [
                'action'  => 'archived',
                'message' => 'تم أرشفة المنتج وإيقافه — لا يمكن حذفه نهائياً لارتباطه بـ ' . $invoiceCount . ' سجل فاتورة.',
            ];
        }

        // ── Tier 3: Safe hard delete ────────────────────────────────
        $this->branchStocks()->delete();   // clean up empty rows
        $this->delete();

        return [
            'action'  => 'deleted',
            'message' => 'تم حذف المنتج نهائياً.',
        ];
    }

    /**
     * Restore an archived product (re-activate it).
     */
    public function restore(): void
    {
        $this->update(['is_active' => true]);
    }
}
