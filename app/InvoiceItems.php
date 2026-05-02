<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LensStockEntry;

class InvoiceItems extends Model
{
    protected $table = 'invoice_items';

    protected $guarded = ['id'];

    protected $casts = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function lens()
    {
        return $this->belongsTo(glassLense::class, 'product_id', 'product_id');
    }

    // ✅ Accessor للحصول على الـ stockable
    public function getStockableAttribute()
    {
        if ($this->type === 'lens') {
            return $this->lens;
        }

        return $this->product;
    }

    public function getStockAmountAttribute()
    {
        if ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)->first();
            if (!$lens) return 0;
            return LensStockEntry::availableFor($lens->id, $this->invoice->branch_id);
        }

        $product = Product::where('product_id', $this->product_id)->first();

        if (!$product) return 0;

        $branchStock = BranchStock::where('branch_id', $this->invoice->branch_id)
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $product->id)
            ->first();

        return $branchStock ? $branchStock->quantity : 0;
    }


    // العلاقات القديمة (محتفظ بيها للـ backward compatibility)
    public function getProductItem() {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    public function getLenseItem() {
        return $this->hasOne(glassLense::class, 'product_id', 'product_id');
    }

   /* protected function product()
    {
        return $this->hasOne(Product::class, 'product_id','product_id');
    }*/

    public function getItemDescription() {
        if($this->type == 'product') {
            return $this->hasOne(Product::class, 'product_id', 'product_id');
        } else {
            return $this->hasOne(glassLense::class, 'product_id', 'product_id');
        }
    }

    // ✅ علاقة الفاتورة
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // ====================================================
// ADD TO InvoiceItem MODEL (app/InvoiceItems.php)
// ====================================================

    /**
     * Reserve stock for this item
     */
    public function reserveStock()
    {
        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }

            // ✅ Get balance BEFORE reserve
            $stock = $product->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            $product->reserveInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER reserve
            $stock->refresh(); // Refresh to get updated values
            $balanceAfter = $stock->quantity; // Quantity doesn't change on reserve

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'reserve',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Reserved for invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)
                ->where('branch_id', $invoice->branch_id)
                ->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            // ✅ Get balance BEFORE
            $stock = $lens->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            $lens->reserveInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER
            $stock->refresh();
            $balanceAfter = $stock->quantity;

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'stockable_type' => 'App\glassLense',
                'stockable_id' => $lens->id,
                'type' => 'reserve',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Reserved for invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);
        }

        \Log::info("Stock reserved: {$this->type} {$this->product_id} x{$this->quantity}");
    }

    /**
     * Unreserve stock for this item
     */
    public function unreserveStock()
    {
        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }

            // ✅ Get balance BEFORE
            $stock = $product->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            $product->unreserveInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER
            $stock->refresh();
            $balanceAfter = $stock->quantity;

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'unreserve',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Unreserved from invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)
                ->where('branch_id', $invoice->branch_id)
                ->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            // ✅ Get balance BEFORE
            $stock = $lens->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            $lens->unreserveInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER
            $stock->refresh();
            $balanceAfter = $stock->quantity;

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'stockable_type' => 'App\glassLense',
                'stockable_id' => $lens->id,
                'type' => 'unreserve',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Unreserved from invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);
        }

        \Log::info("Stock unreserved: {$this->type} {$this->product_id} x{$this->quantity}");
    }

    /**
     * Reduce stock for this item (on delivery)
     */
    /*public function reduceStock()
    {
        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }

            $product->reduceStockInBranch($invoice->branch_id, $this->quantity);

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'out',
                'quantity' => $this->quantity,
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Delivered - Invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)
                ->where('branch_id', $invoice->branch_id)
                ->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            $lens->reduceStockInBranch($invoice->branch_id, $this->quantity);
            $lens->decrement('amount', $this->quantity); // Old field

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'stockable_type' => 'App\glassLense',
                'stockable_id' => $lens->id,
                'type' => 'out',
                'quantity' => $this->quantity,
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Delivered - Invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);
        }

        \Log::info("Stock reduced: {$this->type} {$this->product_id} x{$this->quantity}");
    }*/

    /**
     * Return stock for this item (on cancellation)
     */
    /*public function returnStock()
    {
        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }

            $product->addStockInBranch($invoice->branch_id, $this->quantity);

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'in',
                'quantity' => $this->quantity,
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Returned from canceled invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)
                ->where('branch_id', $invoice->branch_id)
                ->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            $lens->addStockInBranch($invoice->branch_id, $this->quantity);
            $lens->increment('amount', $this->quantity); // Old field

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'stockable_type' => 'App\glassLense',
                'stockable_id' => $lens->id,
                'type' => 'in',
                'quantity' => $this->quantity,
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Returned from canceled invoice #{$invoice->invoice_code}",
                'user_id' => $user ? $user->id : null,
            ]);
        }

        \Log::info("Stock returned: {$this->type} {$this->product_id} x{$this->quantity}");
    }*/

    /**
     * Cancel this item
     */
    public function cancelItem($reason = null)
    {
        $this->status = 'canceled';
       // $this->canceled_at = now();
        //$this->cancellation_reason = $reason;
        $this->save();

        \Log::info("Item {$this->id} canceled: {$this->type} {$this->product_id}");
    }

/**
* Deliver this item (reduce stock)
* Safe to call multiple times - will only reduce once
*/
    public function deliverItem()
    {
        // ✅ Check if already delivered
        if ($this->is_delivered) {
            \Log::info("Item {$this->id} already delivered, skipping");
            return; // Skip - already delivered
        }

        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }


            // ✅ Get balance BEFORE
            $stock = $product->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            // Reduce stock
            $product->reduceStockInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER
            $stock->refresh();
            $balanceAfter = $stock->quantity;

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'out',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Delivered - Invoice #{$invoice->invoice_code} - Item #{$this->id}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            // Determine side from item direction (R / L)
            $rawDir    = trim($this->direction ?? '');
            $lenseSide = in_array($rawDir, ['R', 'L']) ? $rawDir : null;

            // New: use lens_stock_entries system
            LensStockEntry::create([
                'branch_id'      => $invoice->branch_id,
                'glass_lense_id' => $lens->id,
                'side'           => $lenseSide,
                'direction'      => 'out',
                'quantity'       => $this->quantity,
                'source_type'    => 'invoice_delivery',
                'source_id'      => $invoice->id,
                'notes'          => "Delivered - Invoice #{$invoice->invoice_code} - Item #{$this->id}",
                'user_id'        => $user ? $user->id : null,
            ]);
        }

        // ✅ Mark as delivered
        $this->is_delivered = true;
        $this->delivered_at = now();
        $this->save();

        \Log::info("Item delivered: {$this->type} {$this->product_id} x{$this->quantity}");
    }

    /**
     * Un-deliver this item (return stock)
     * Safe to call - will only return if was delivered
     */
    public function undeliverItem()
    {
        if (!$this->is_delivered) {
            \Log::info("Item {$this->id} was not delivered, skipping return");
            return; // Skip - was not delivered
        }

        $invoice = $this->invoice;
        $user = auth()->user();

        if ($this->type === 'product') {
            $product = Product::where('product_id', $this->product_id)->first();

            if (!$product) {
                throw new \Exception("Product {$this->product_id} not found");
            }

            // ✅ Get balance BEFORE
            $stock = $product->stockInBranch($invoice->branch_id);
            $balanceBefore = $stock ? $stock->quantity : 0;

            // Return stock
            $product->addStockInBranch($invoice->branch_id, $this->quantity);

            // ✅ Get balance AFTER
            $stock->refresh();
            $balanceAfter = $stock->quantity;

            StockMovement::create([
                'branch_id' => $invoice->branch_id,
                'product_id' => $this->product_id,
                'stockable_type' => 'App\Product',
                'stockable_id' => $product->id,
                'type' => 'in',
                'quantity' => $this->quantity,
                'balance_before' => $balanceBefore,      // ✅ Added
                'balance_after' => $balanceAfter,        // ✅ Added
                'reference_type' => 'App\Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Returned - Invoice #{$invoice->invoice_code} - Item #{$this->id}",
                'user_id' => $user ? $user->id : null,
            ]);

        } elseif ($this->type === 'lens') {
            $lens = glassLense::where('product_id', $this->product_id)->first();

            if (!$lens) {
                throw new \Exception("Lens {$this->product_id} not found");
            }

            // Determine side from item direction (R / L)
            $rawDir    = trim($this->direction ?? '');
            $lenseSide = in_array($rawDir, ['R', 'L']) ? $rawDir : null;

            // New: reverse the stock_out entry
            LensStockEntry::create([
                'branch_id'      => $invoice->branch_id,
                'glass_lense_id' => $lens->id,
                'side'           => $lenseSide,
                'direction'      => 'in',
                'quantity'       => $this->quantity,
                'source_type'    => 'invoice_return',
                'source_id'      => $invoice->id,
                'notes'          => "Returned - Invoice #{$invoice->invoice_code} - Item #{$this->id}",
                'user_id'        => $user ? $user->id : null,
            ]);
        }

        // ✅ Mark as not delivered
        $this->is_delivered = false;
        $this->item_delivered_at = null;
        $this->save();

        \Log::info("Item undelivered: {$this->type} {$this->product_id} x{$this->quantity}");
    }

    /**
     * OLD METHODS - Keep for backward compatibility but update them
     */
    public function reduceStock()
    {
        $this->deliverItem();
    }

    public function returnStock()
    {
        $this->undeliverItem();
    }

}
