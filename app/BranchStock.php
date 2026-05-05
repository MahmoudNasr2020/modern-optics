<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    protected $table = 'branch_stock';

    protected $fillable = [
        'branch_id',
        'product_id',
        'stockable_type',
        'stockable_id',
        'quantity',
        'min_quantity',
        'max_quantity',
        'reserved_quantity',
        'average_cost',
        'last_cost',
        'total_in',
        'total_out',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'average_cost' => 'decimal:2',
        'last_cost' => 'decimal:2',
        'total_in' => 'integer',
        'total_out' => 'integer',
    ];

    /**
     * ====================================================
     * RELATIONSHIPS
     * ====================================================
     */

    // Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ✅ NEW: Polymorphic relationship to Product OR glassLense
    public function stockable()
    {
        return $this->morphTo();
    }

    // ✅ Keep old relationship for backward compatibility
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Movements
    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'stockable_type', 'stockable_type')
            ->where('stockable_id', $this->stockable_id)
            ->where('branch_id', $this->branch_id)
            ->latest();
    }

    /**
     * ====================================================
     * ACCESSORS
     * ====================================================
     */

    // Available quantity
    public function getAvailableQuantityAttribute()
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    // Stock percentage
    public function getStockPercentageAttribute()
    {
        if ($this->max_quantity <= 0) {
            return 0;
        }
        return round(($this->quantity / $this->max_quantity) * 100, 2);
    }

    // Stock status
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return [
                'status' => 'out_of_stock',
                'label'  => 'Out of Stock',
                'class'  => 'danger',
                'icon'   => 'fa-times-circle'
            ];
        } elseif ($this->quantity <= $this->min_quantity) {
            return [
                'status' => 'low_stock',
                'label'  => 'Low Stock',
                'class'  => 'warning',
                'icon'   => 'fa-exclamation-triangle'
            ];
        } elseif ($this->quantity >= $this->max_quantity) {
            return [
                'status' => 'over_stock',
                'label'  => 'Overstock',
                'class'  => 'info',
                'icon'   => 'fa-info-circle'
            ];
        } else {
            return [
                'status' => 'normal',
                'label'  => 'In Stock',
                'class'  => 'success',
                'icon'   => 'fa-check-circle'
            ];
        }
    }

    // ✅ NEW: Get item description (Product or Lens)
    public function getDescriptionAttribute()
    {
        if ($this->stockable) {
            return $this->stockable->description ?? 'N/A';
        }

        // Fallback to old product relationship
        if ($this->product) {
            return $this->product->description;
        }

        return 'Unknown Item';
    }

    // ✅ NEW: Get item code/ID
    public function getItemCodeAttribute()
    {
        if ($this->stockable) {
            return $this->stockable->product_id ?? $this->stockable->id;
        }

        if ($this->product) {
            return $this->product->product_id;
        }

        return 'N/A';
    }

    // ✅ NEW: Get item type name
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
     * SCOPES
     * ====================================================
     */

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'min_quantity')
            ->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeOverStock($query)
    {
        return $query->whereColumn('quantity', '>=', 'max_quantity');
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // ✅ NEW: Scope for products only
    public function scopeProducts($query)
    {
        return $query->where('stockable_type', 'App\\Product');
    }

    // ✅ NEW: Scope for lenses only
    public function scopeLenses($query)
    {
        return $query->where('stockable_type', 'App\\glassLense');
    }

    /**
     * ====================================================
     * METHODS
     * ====================================================
     */

    // Add quantity
    public function addQuantity($quantity, $cost = null)
    {
        $this->increment('quantity', $quantity);
        $this->increment('total_in', $quantity);

        if ($cost !== null) {
            $this->last_cost = $cost;

            // Calculate average cost
            if ($this->average_cost === null) {
                $this->average_cost = $cost;
            } else {
                $totalCost = ($this->quantity * $this->average_cost) + ($quantity * $cost);
                $this->average_cost = $totalCost / ($this->quantity + $quantity);
            }
        }

        $this->save();
        return $this;
    }

    // Reduce quantity
    /*public function reduceQuantity($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        $this->decrement('quantity', $quantity);
        $this->increment('total_out', $quantity);

        return $this;
    }*/

   /* public function reduceQuantity($quantity)
    {
        // Check if we have enough available stock
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        // ✅ Reduce from quantity (actual stock)
        $this->decrement('quantity', $quantity);

        // ✅ Reduce from reserved_quantity (if any)
        // We reduce the minimum of (quantity, reserved_quantity)
        $toUnreserve = min($quantity, $this->reserved_quantity);
        if ($toUnreserve > 0) {
            $this->decrement('reserved_quantity', $toUnreserve);
        }

        // Track total out
        $this->increment('total_out', $quantity);

        return $this;
    }

    // Reserve quantity
    public function reserveQuantity($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Insufficient stock to reserve');
        }

        $this->increment('reserved_quantity', $quantity);
        return $this;
    }*/

    public function reduceQuantity($quantity)
    {
        // ✅ Check actual quantity (not available)
        // Because if it was reserved, it's already "allocated" to this invoice
        if ($this->quantity < $quantity) {
            throw new \Exception("Insufficient stock. Have {$this->quantity}, need {$quantity}");
        }

        // ✅ Reduce from quantity (actual stock)
        $this->decrement('quantity', $quantity);
        $this->refresh();

        // ✅ Reduce from reserved_quantity (if any)
        $toUnreserve = min($quantity, $this->reserved_quantity);
        if ($toUnreserve > 0) {
            $this->decrement('reserved_quantity', $toUnreserve);
        }

        // Track total out
        $this->increment('total_out', $quantity);

        // 🔔 Trigger low-stock / out-of-stock notifications
        $this->checkAndNotifyStockLevel();

        return $this;
    }

    /**
     * Check stock level and fire notification if needed.
     * Avoids spamming: only fires once per transition (not on every reduce).
     */
    protected function checkAndNotifyStockLevel(): void
    {
        try {
            $item   = $this->stockable;
            $branch = $this->branch;

            if (!$item || !$branch) return;

            if ($this->quantity <= 0) {
                \App\Services\NotificationService::outOfStock($item, $branch);
            } elseif ($this->quantity <= $this->min_quantity) {
                \App\Services\NotificationService::lowStock($item, $branch);
            }
        } catch (\Exception $e) {
            // Never let notification errors break the main flow
            \Log::warning('Stock notification failed: ' . $e->getMessage());
        }
    }

    public function resetStock()
    {
        if ($this->reserved_quantity > 0) {
            throw new \Exception("Cannot reset stock, reserved quantity is {$this->reserved_quantity}");
        }

        $this->quantity = 0;
        $this->save();

        return $this;
    }

    public function forceResetStock($reason = null)
    {
        $this->quantity = 0;
        $this->reserved_quantity = 0;

        if ($reason) {
            $this->notes = $reason;
        }

        $this->save();
        return $this;
    }

    public function reduceQuantityManually($quantity)
    {
        // Check if we have enough actual quantity
        if ($this->quantity < $quantity) {
            throw new \Exception("Insufficient stock. Have {$this->quantity}, need {$quantity}");
        }

        // Calculate what will remain after reduction
        $remainingQuantity = $this->quantity - $quantity;

        // Check if remaining quantity will cover reserved quantity
        if ($remainingQuantity < $this->reserved_quantity) {
            throw new \Exception(
                "Cannot reduce {$quantity} units. " .
                "After reduction, remaining quantity ({$remainingQuantity}) " .
                "would be less than reserved quantity ({$this->reserved_quantity}). " .
                "Maximum you can reduce is " . ($this->quantity - $this->reserved_quantity) . " units."
            );
        }

        // All checks passed, reduce the quantity
        $this->decrement('quantity', $quantity);

        // Track total out
        $this->increment('total_out', $quantity);

        return $this;
    }

    /**
     * Reserve quantity (for pending invoices)
     */
    public function reserveQuantity($quantity)
    {
        // ✅ Check available (CORRECT)
        // Because we're allocating NEW stock
        if ($this->available_quantity < $quantity) {
            throw new \Exception("Insufficient available stock. Available: {$this->available_quantity}, need: {$quantity}");
        }

        $this->increment('reserved_quantity', $quantity);
        return $this;
    }

    // Unreserve quantity
    public function unreserveQuantity($quantity)
    {
        $this->decrement('reserved_quantity', min($quantity, $this->reserved_quantity));
        return $this;
    }

    // Update limits
    public function updateLimits($minQuantity, $maxQuantity)
    {
        $this->update([
            'min_quantity' => $minQuantity,
            'max_quantity' => $maxQuantity,
        ]);

        return $this;
    }

    // Recent movements
    public function recentMovements($limit = 10)
    {
        return $this->movements()->limit($limit)->get();
    }
}
