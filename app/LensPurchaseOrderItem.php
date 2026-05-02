<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LensPurchaseOrderItem extends Model
{
    protected $table = 'lens_purchase_order_items';

    protected $fillable = [
        'purchase_order_id', 'invoice_item_id', 'glass_lense_id',
        'lens_product_id', 'quantity', 'received_quantity', 'unit_cost', 'notes',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(LensPurchaseOrder::class, 'purchase_order_id');
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItems::class, 'invoice_item_id');
    }

    public function lens()
    {
        return $this->belongsTo(glassLense::class, 'glass_lense_id');
    }
}
