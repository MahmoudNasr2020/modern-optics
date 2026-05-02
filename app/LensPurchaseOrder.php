<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LensPurchaseOrder extends Model
{
    protected $table = 'lens_purchase_orders';

    protected $fillable = [
        'po_number', 'invoice_id', 'branch_id', 'lab_id', 'lab_name',
        'status', 'ordered_by', 'received_by', 'notes', 'ordered_at', 'received_at',
        'is_reorder', 'original_po_id',
    ];

    protected $dates = ['ordered_at', 'received_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function lab()
    {
        return $this->belongsTo(LensLab::class, 'lab_id');
    }

    public function orderedBy()
    {
        return $this->belongsTo(\App\User::class, 'ordered_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(\App\User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(LensPurchaseOrderItem::class, 'purchase_order_id');
    }

    public function isPending()   { return $this->status === 'pending'; }
    public function isSent()      { return $this->status === 'sent'; }
    public function isReceived()  { return $this->status === 'received'; }
    public function isCancelled() { return $this->status === 'cancelled'; }

    public static function generatePoNumber()
    {
        $year  = now()->format('Y');
        $last  = static::whereYear('created_at', $year)->lockForUpdate()->max('id') ?? 0;
        $seq   = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        return "PO-{$year}-{$seq}";
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':   return '<span class="label label-warning">Pending</span>';
            case 'sent':      return '<span class="label label-info">Sent to Lab</span>';
            case 'received':  return '<span class="label label-success">Received</span>';
            case 'cancelled': return '<span class="label label-danger">Cancelled</span>';
            default:          return '<span class="label label-default">' . $this->status . '</span>';
        }
    }
}
