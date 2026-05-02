<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockTransfer extends Model
{
    protected $fillable = [
        'transfer_number',
        'from_branch_id',
        'to_branch_id',
        'product_id',           // Keep for backward compatibility
        'stockable_type',       // ✅ NEW: Polymorphic type
        'stockable_id',         // ✅ NEW: Polymorphic ID
        'quantity',
        'transfer_date',
        'approved_date',
        'shipped_date',
        'received_date',
        'status',
        'created_by',
        'approved_by',
        'shipped_by',
        'received_by',
        'notes',
        'rejection_reason',
        'priority',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'approved_date' => 'date',
        'shipped_date' => 'date',
        'received_date' => 'date',
        'quantity' => 'integer',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transfer) {
            if (empty($transfer->transfer_number)) {
                $transfer->transfer_number = self::generateTransferNumber();
            }
            if (empty($transfer->transfer_date)) {
                $transfer->transfer_date = now();
            }
        });
    }

    public static function generateTransferNumber()
    {
        $date = now()->format('Ymd');
        $lastTransfer = self::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastTransfer ? (intval(substr($lastTransfer->transfer_number, -4)) + 1) : 1;
        return 'ST-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * ====================================================
     * RELATIONSHIPS
     * ====================================================
     */

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    // ✅ NEW: Polymorphic relationship
    public function stockable()
    {
        return $this->morphTo();
    }

    // Keep old relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipped_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where(function($q) use ($branchId) {
            $q->where('from_branch_id', $branchId)
                ->orWhere('to_branch_id', $branchId);
        });
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

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending'      => ['label' => 'Pending', 'class' => 'warning', 'icon' => 'fa-clock'],
            'approved'     => ['label' => 'Approved', 'class' => 'info', 'icon' => 'fa-check'],
            'in_transit'   => ['label' => 'In Transit', 'class' => 'primary', 'icon' => 'fa-truck'],
            'received'     => ['label' => 'Received', 'class' => 'success', 'icon' => 'fa-check-circle'],
            'canceled'     => ['label' => 'Canceled', 'class' => 'danger', 'icon' => 'fa-times-circle'],
        ];

        return $statuses[$this->status] ?? ['label' => 'unknown', 'class' => 'secondary', 'icon' => 'fa-question'];
    }

    public function getPriorityLabelAttribute()
    {
        $priorities = [
            'low'    => ['label' => 'Low', 'class' => 'secondary'],
            'medium' => ['label' => 'Medium', 'class' => 'info'],
            'high'   => ['label' => 'High', 'class' => 'warning'],
            'urgent' => ['label' => 'Urgent', 'class' => 'danger'],
        ];

        return $priorities[$this->priority] ?? ['label' => 'Medium', 'class' => 'info'];
    }

    public function getDaysOldAttribute()
    {
        return $this->transfer_date->diffInDays(now());
    }

    // ✅ NEW: Get item description
    public function getItemDescriptionAttribute()
    {
        if ($this->stockable) {
            return $this->stockable->description ?? 'N/A';
        }

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
     * METHODS
     * ====================================================
     */

    public function approve($userId)
    {
        DB::beginTransaction();
        try {
            // Get stock from source branch
            $fromStock = BranchStock::where('branch_id', $this->from_branch_id)
                ->where('stockable_type', $this->stockable_type)
                ->where('stockable_id', $this->stockable_id)
                ->lockForUpdate()
                ->first();

            if (!$fromStock || $fromStock->available_quantity < $this->quantity) {
                throw new \Exception('Insufficient stock in source branch');
            }

            $balanceBefore = $fromStock->quantity;

            // Reduce from source
            $fromStock->reduceQuantity($this->quantity);

            // Create movement
            StockMovement::create([
                'branch_id' => $this->from_branch_id,
                'product_id' => $this->product_id,  // For backward compatibility
                'stockable_type' => $this->stockable_type,
                'stockable_id' => $this->stockable_id,
                'type' => 'transfer_out',
                'quantity' => $this->quantity,
                'reference_type' => self::class,
                'reference_id' => $this->id,
                'notes' => "Transfer to {$this->toBranch->name} - {$this->transfer_number}",
                'balance_before' => $balanceBefore,
                'balance_after' => $fromStock->quantity,
                'user_id' => $userId,
            ]);

            // Update transfer status
            $this->update([
                'status' => 'approved',
                'approved_by' => $userId,
                'approved_date' => now(),
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function ship($userId)
    {
        if ($this->status !== 'approved') {
            throw new \Exception('Transfer must be approved first');
        }

        $this->update([
            'status' => 'in_transit',
            'shipped_by' => $userId,
            'shipped_date' => now(),
        ]);

        return true;
    }

    public function receive($userId)
    {
        if (!in_array($this->status, ['approved', 'in_transit'])) {
            throw new \Exception('Cannot receive this transfer');
        }

        DB::beginTransaction();
        try {
            // Add to destination branch
            $toStock = BranchStock::firstOrCreate(
                [
                    'branch_id' => $this->to_branch_id,
                    'stockable_type' => $this->stockable_type,
                    'stockable_id' => $this->stockable_id,
                ],
                [
                    'product_id' => $this->product_id,  // For backward compatibility
                    'quantity' => 0,
                    'min_quantity' => $this->stockable_type === 'App\\glassLense' ? 2 : 5,
                    'max_quantity' => $this->stockable_type === 'App\\glassLense' ? 50 : 100,
                ]
            );

            $balanceBefore = $toStock->quantity;

            // Add quantity
            $toStock->addQuantity($this->quantity);

            // Create movement
            StockMovement::create([
                'branch_id' => $this->to_branch_id,
                'product_id' => $this->product_id,  // For backward compatibility
                'stockable_type' => $this->stockable_type,
                'stockable_id' => $this->stockable_id,
                'type' => 'transfer_in',
                'quantity' => $this->quantity,
                'reference_type' => self::class,
                'reference_id' => $this->id,
                'notes' => "Transfer from {$this->fromBranch->name} - {$this->transfer_number}",
                'balance_before' => $balanceBefore,
                'balance_after' => $toStock->quantity,
                'user_id' => $userId,
            ]);

            // Update transfer status
            $this->update([
                'status' => 'received',
                'received_by' => $userId,
                'received_date' => now(),
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancel($reason = null)
    {
        if ($this->status === 'received') {
            throw new \Exception('Cannot cancel a received transfer');
        }

        DB::beginTransaction();
        try {
            // If approved, return stock to source
            if (in_array($this->status, ['approved', 'in_transit'])) {
                $fromStock = BranchStock::where('branch_id', $this->from_branch_id)
                    ->where('stockable_type', $this->stockable_type)
                    ->where('stockable_id', $this->stockable_id)
                    ->first();

                if ($fromStock) {
                    $balanceBefore = $fromStock->quantity;

                    $fromStock->addQuantity($this->quantity);

                    StockMovement::create([
                        'branch_id' => $this->from_branch_id,
                        'product_id' => $this->product_id,
                        'stockable_type' => $this->stockable_type,
                        'stockable_id' => $this->stockable_id,
                        'type' => 'adjustment',
                        'quantity' => $this->quantity,
                        'reason' => 'Transfer Canceled',
                        'reference_type' => self::class,
                        'reference_id' => $this->id,
                        'notes' => "Return from canceled transfer - {$this->transfer_number}",
                        'balance_before' => $balanceBefore,
                        'balance_after' => $fromStock->quantity,
                        'user_id' => \Auth::id(),
                    ]);
                }
            }

            $this->update([
                'status' => 'canceled',
                'rejection_reason' => $reason,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
