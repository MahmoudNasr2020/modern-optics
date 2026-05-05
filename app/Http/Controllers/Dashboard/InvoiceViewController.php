<?php

namespace App\Http\Controllers\Dashboard;

use App\CashierTransaction;
use App\Facades\Settings;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItems;
use App\Payments;
use App\Product;
use App\Services\InvoiceWhatsAppNotifier;
use App\Services\StockService;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceViewController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.spatie:view-invoices')
            ->only(['show', 'details']);

        $this->middleware('permission.spatie:view-pending-invoices')
            ->only(['pendingInvoices']);

        $this->middleware('permission.spatie:view-returned-invoices')
            ->only(['returnInvoices']);

        $this->middleware('permission.spatie:create-invoices')
            ->only(['create', 'store']);

        $this->middleware('permission.spatie:edit-invoices')
            ->only(['edit', 'update']);

        /*$this->middleware('permission.spatie:delete-invoices')
            ->only(['destroy']);*/

        /*$this->middleware('permission.spatie:print-invoices')
            ->only(['print']);*/

        $this->middleware('permission.spatie:add-payments')
            ->only(['addPayment']);

        $this->middleware('permission.spatie:delete-payments')
            ->only(['deletePayment']);
    }

    /**
     * ====================================================
     * PENDING INVOICE
     * ====================================================
     */
    public function pendingInvoices(Request $request)
    {
        $user     = auth()->user();
        $branches = $user->getAccessibleBranches();

        // Branch access control: employees only see their own branch
        $branchId       = $request->input('branch_id');
        $filterBranchId = $user->getFilterBranchId($branchId);

        $invoices = Invoice::whereIn('status', ['pending', 'Under Process', 'ready'])
            ->with([
                'customer' => function ($query) {
                    $query->select('customer_id', 'english_name');
                },
                'user' => function ($query) {
                    $query->select('id', 'first_name');
                },
                'doctor' => function ($query) {
                    $query->select('id', 'name', 'code');
                },
                'branch' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            // Branch filter (respects user access level)
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('branch_id', $filterBranchId);
            })
            // Filter by invoice code
            ->when($request->invoice_code, function ($q, $value) {
                $q->where('invoice_code', 'like', "%{$value}%");
            })
            // Filter by customer code
            ->when($request->customer_code, function ($q, $value) {
                $q->where('customer_id', $value);
            })
            // Filter by customer name
            ->when($request->customer_name, function ($q, $value) {
                $q->whereHas('customer', function ($query) use ($value) {
                    $query->where('english_name', 'like', "%{$value}%");
                });
            })
            // Filter by creation date
            ->when($request->creation_date, function ($q, $value) {
                $q->whereDate('created_at', $value);
            })
            // Filter by status
            ->when($request->status, function ($q, $value) {
                $q->where('status', $value);
            })
            // Filter by remaining balance
            ->when($request->remaining_balance == '1', function ($q) {
                $q->where('remaining', '>', 0);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(20)
            ->appends($request->except('page'));

        // Flag lens items and active POs
        $invoiceIds = $invoices->pluck('id');

        $lensInvoiceIds = \DB::table('invoice_items')
            ->whereIn('invoice_id', $invoiceIds)
            ->where('type', 'lens')
            ->pluck('invoice_id')
            ->unique()->toArray();

        $poInvoiceIds = \App\LensPurchaseOrder::whereIn('invoice_id', $invoiceIds)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('invoice_id')
            ->unique()->toArray();

        foreach ($invoices as $invoice) {
            $invoice->has_lens_items = in_array($invoice->id, $lensInvoiceIds);
            $invoice->has_active_po  = in_array($invoice->id, $poInvoiceIds);
        }

        return view('dashboard.pages.invoice-new.pending-invoice', compact('invoices', 'branches'));
    }


    // ==========================================
// GET RETURN INVOICES
// ==========================================
    public function returnInvoices(Request $request)
    {

        // If no search parameters, return empty collection
        if (!$request->invoice_code && !$request->customer_code && !$request->product_id) {
            $invoices = collect();
            return view('dashboard.pages.invoice-new.return-invoice', compact('invoices'));
        }

        $query = Invoice::leftJoin('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select([
                'invoices.*',
                'users.first_name as user_name',
                'customers.english_name as customer_name'
            ])
            // Include delivered, pending, Under Process, and canceled invoices
            ->whereIn('invoices.status', ['delivered', 'pending', 'Under Process','ready']);

        // Filter by invoice code
        if ($request->invoice_code) {
            $query->where('invoices.invoice_code', 'like', '%' . $request->invoice_code . '%');
        }

        // Filter by customer code
        if ($request->customer_code) {
            $query->where('invoices.customer_id', $request->customer_code);
        }

        // Filter by product/article ID
        if ($request->product_id) {
            $query->where('invoice_items.product_id', $request->product_id);
        }

         $invoices = $query->distinct()->get();

        return view('dashboard.pages.invoice-new.return-invoice', compact('invoices'));
    }


    // ==========================================
// POST RETURN INVOICE (Using StockService)
// ==========================================
    /*public function postReturnInvoice(Request $request)
    {
        $invoice_code = $request->InvoiceID;
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Check if already canceled
        if (strtolower($invoice->status) === 'canceled') {
            return response()->json(['error' => 'Invoice already canceled'], 400);
        }

        DB::beginTransaction();
        try {

            // ✅ STEP 1: Update Invoice Status
            $invoice->status = 'canceled';
            $invoice->notes = $request->invoice_notes;
            $invoice->return_reason = $request->return_reason;
            $invoice->canceled_at = now();
            $invoice->canceled_by = auth()->user()->id;
            $invoice->save();

            // ✅ STEP 2: Process Payment Refunds
            $originalPayments = Payments::where('invoice_id', $invoice->id)
                ->where(function ($query) {
                    $query->where('is_refund', false)
                        ->orWhereNull('is_refund');
                })
                ->get();

            foreach ($originalPayments as $payment) {

                // Create refund payment record (negative amount)
                $refundPayment = Payments::create([
                    'invoice_id' => $invoice->id,
                    'type' => $payment->type,
                    'bank' => $payment->bank,
                    'card_number' => $payment->card_number,
                    'expiration_date' => $payment->expiration_date,
                    'currency' => $payment->currency ?? 'QAR',
                    'payed_amount' => -abs($payment->payed_amount),
                    'exchange_rate' => $payment->exchange_rate ?? 1,
                    'local_payment' => -abs($payment->local_payment ?? $payment->payed_amount),
                    'beneficiary' => auth()->user()->id,
                    'is_refund' => true,
                    //'refund_of_payment_id' => $payment->id,
                    'created_at' => now(),
                ]);

                // Create cashier transaction for the refund
                CashierTransaction::create([
                    'transaction_type' => 'refund',
                    'payment_type' => $payment->type,
                    'amount' => abs($payment->payed_amount),
                    'currency' => $payment->currency ?? 'QAR',
                    'exchange_rate' => $payment->exchange_rate ?? 1,
                    'amount_in_sar' => -abs($payment->payed_amount),
                    'invoice_id' => $invoice->id,
                    'payment_id' => $refundPayment->id,
                    'customer_id' => $invoice->customer_id,
                    'bank' => $payment->bank,
                    'card_number' => $payment->card_number,
                    'notes' => "Refund for invoice #{$invoice->invoice_code} - {$request->return_reason}",
                    'cashier_id' => auth()->user()->id,
                    'transaction_date' => now(),
                ]);
            }

            // ✅ STEP 3: Return Stock Using StockService
            foreach ($invoice->invoiceItems as $item) {


                // Determine type: 'product' or 'lens'
                $type = $item->type === 'product' ? 'product' : 'lens';

                // Resolve item ID safely (product/lens may have been deleted)
                $itemId = $type === 'product'
                    ? (optional($item->product)->id ?? null)
                    : (optional($item->lens)->id    ?? null);

                if (!$itemId) {
                    $item->status = 'canceled';
                    $item->save();
                    continue;
                }

                // Return stock using StockService
                StockService::returnStock(
                    $invoice->branch_id,           // Branch ID
                    $type,                         // Type: 'product' or 'lens'
                    $itemId,                       // Item ID
                    $item->quantity,              // Quantity to return
                    auth()->user()->id,           // User ID
                    null,                         // Cost (null for returns)
                );

                $item->status = 'canceled';
                $item->save();

                \Log::info("Stock returned: {$type} {$item->product_id} +{$item->quantity} to branch {$invoice->branch_id}");
            }

            // ✅ STEP 4: Update Invoice Totals
            $invoice->paied = 0;
            $invoice->remaining = 0;
            $invoice->save();

            DB::commit();

            \Log::info("Invoice {$invoice->invoice_code} returned successfully by user " . auth()->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Invoice returned successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Failed to return invoice {$invoice_code}: " . $e->getMessage());

            return response()->json([
                'error' => 'Failed to return invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }*/

    // ====================================================
// REPLACE postReturnInvoice METHOD
// ====================================================

    public function postReturnInvoice(Request $request)
    {
        $invoice_code = $request->InvoiceID;
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Check if already canceled
        if (strtolower($invoice->status) === 'canceled') {
            return response()->json(['error' => 'Invoice already canceled'], 400);
        }

        try {
            // ✅ Use helper method to cancel invoice
            $invoice->cancelInvoice(
                $request->return_reason,
                $request->invoice_notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice returned successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to return invoice {$invoice_code}: " . $e->getMessage());

            return response()->json([
                'error' => 'Failed to return invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ====================================================
     * SHOW INVOICE DETAILS
     * ====================================================
     */
    public function show($invoice_code)
    {
         $invoice = Invoice::with([
            'customer',
            'user',
            'doctor',
            'invoiceItems.product',
            'invoiceItems.lens'
        ])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        $payments = Payments::where('invoice_id', $invoice->id)
            ->with('getBenficiary')
            ->orderBy('created_at', 'DESC')
            ->get();


        return view('dashboard.pages.invoice-new.show', compact('invoice', 'payments'));
    }

    /**
     * ====================================================
     * INVOICE DETAILS
     * ====================================================
     */

    public function details(Request $request)
    {
        $invoice_code = $request->InvoiceID;

        // Get invoice
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Get invoice items
        $invoiceItems = InvoiceItems::where('invoice_id', $invoice->id)->get();

        // Get payments
        $payments = Payments::where('invoice_id', $invoice->id)
            ->with('getBenficiary')
            ->get();

        // Process each item
        foreach($invoiceItems as $item) {

            if($item->type == 'product') {
                // ✅ Get product with total_stock
                $product = $item->product;

                $item['name'] = $product->description ?? '-';
                $item['stock'] = $product ? $product->total_stock : 0; // ✅ Total across all branches

                // ✅ Get stock details from invoice branch
                $branchStock = $product ? $product->stockInBranch($invoice->branch_id) : null;
                $item['available_stock'] = $branchStock ? $branchStock->available_quantity : 0;
                $item['reserved_stock'] = $branchStock ? $branchStock->reserved_quantity : 0;

            } else {
                // ✅ Get lens with total_stock
                $lens = $item->lens;

                $item['name'] = $lens->description ?? '-';
                $item['stock'] = $lens ? $lens->total_stock : 0; // ✅ Total across all branches

                // ✅ Get stock details from invoice branch
                $branchStock = $lens ? $lens->stockInBranch($invoice->branch_id) : null;
                $item['available_stock'] = $branchStock ? $branchStock->available_quantity : 0;
                $item['reserved_stock'] = $branchStock ? $branchStock->reserved_quantity : 0;
            }
        }

        return response()->json([
            'invoice' => $invoice,
            'Invoice_items' => $invoiceItems,
            'payments' => $payments
        ]);
    }

    public function edit($invoice_code)
    {
        $invoice = Invoice::with([
            'customer',
            'user',
            'doctor',
            'invoiceItems.product',
            'invoiceItems.lens'
        ])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        $payments = Payments::where('invoice_id', $invoice->id)
            ->with('getBenficiary')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('dashboard.pages.invoice-new.edit', compact('invoice', 'payments'));
    }

    /**
     * ====================================================
     * UPDATE INVOICE
     * ====================================================
     */
    public function update(Request $request, $invoice_code)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,Under Process,ready,delivered',
            'notes' => 'nullable|string',
            'tray_number' => 'nullable|string',
            'ready_items' => 'nullable|array',
            'delivery_items' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoice = Invoice::where('invoice_code', $invoice_code)->firstOrFail();
        $oldStatus = $invoice->status;

        // Check if can mark as delivered
        if ($request->status == 'delivered' && $invoice->remaining > 0) {
            return redirect()->back()
                ->with('error', 'Cannot mark invoice as DELIVERED! Remaining amount: ' . number_format($invoice->remaining, 2) . ' QAR. Please collect the payment first.');
        }

        DB::beginTransaction();
        try {

            // ✅ If changing to delivered, use helper method
            if ($request->status == 'delivered' && $oldStatus != 'delivered') {

                // Mark as delivered (will reduce stock automatically)
                $invoice->markAsDelivered();

                // Update other fields
                $invoice->notes = $request->notes;
                $invoice->tray_number = $request->tray_number;
                $invoice->save();

            } else {
                // ✅ Regular update (no stock changes)
                $invoice->update([
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'tray_number' => $request->tray_number,
                ]);
            }

            // Update item statuses - Ready
            $this->updateItemStatus($invoice->id, $request->ready_items ?? [], 'ready');

            // Update item statuses - Delivery
            $this->updateItemStatus($invoice->id, $request->delivery_items ?? [], 'delivery');

            DB::commit();

            try {
                if (Settings::get('send_whatsapp') == true)
                {
                    if($invoice->status == 'ready') {
                        $whatsapp = app(InvoiceWhatsAppNotifier::class);
                        $whatsapp->sendInvoiceReady($invoice);

                    }
                }
            } catch (\Exception $e) {
                \Log::warning("WhatsApp failed: " . $e->getMessage());
            }

            if($request->status == 'delivered') {
                return redirect()->route('dashboard.invoice.pending', $invoice_code)
                    ->with('success', 'Invoice updated successfully!');
            }
            else{
                return redirect()->route('dashboard.invoice.show', $invoice_code)
                    ->with('success', 'Invoice updated successfully!');
            }




        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    /**
     * ====================================================
     * ADD PAYMENT TO INVOICE
     * ====================================================
     */
    public function addPayment(Request $request, $invoice_code)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:Cash,Atm,visa,Master Card,Gift Voudire',
            'payed_amount' => 'required|numeric|min:0.01',
            'bank' => 'nullable|string',
            'card_number' => 'nullable|string',
            'expiration_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoice = Invoice::where('invoice_code', $invoice_code)->firstOrFail();

        // Check if payment exceeds remaining
        if ($request->payed_amount > $invoice->remaining) {
            return redirect()->back()
                ->with('error', 'Payment amount (' . number_format($request->payed_amount, 2) . ' QAR) exceeds remaining amount (' . number_format($invoice->remaining, 2) . ' QAR)');
        }

        DB::beginTransaction();
        try {
            // Create payment
            $payment = Payments::create([
                'invoice_id' => $invoice->id,
                'type' => $request->payment_type,
                'bank' => $request->bank,
                'card_number' => $request->card_number,
                'expiration_date' => $request->expiration_date,
                'payed_amount' => $request->payed_amount,
                'currency' => 'QAR',
                'exchange_rate' => 1,
                'local_payment' => $request->payed_amount,
                'beneficiary' => auth()->id(),
                'is_refund' => false,
            ]);

            // Create cashier transaction
            CashierTransaction::create([
                'transaction_type' => 'sale',
                'payment_type' => $request->payment_type,
                'amount' => $request->payed_amount,
                'currency' => 'QAR',
                'exchange_rate' => 1,
                'amount_in_sar' => $request->payed_amount,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'customer_id' => $invoice->customer_id,
                'bank' => $request->bank,
                'card_number' => $request->card_number,
                'cashier_id' => auth()->id(),
                'transaction_date' => now(),
            ]);

            // Update invoice amounts
            $invoice->paied += $request->payed_amount;
            $invoice->remaining -= $request->payed_amount;
            $invoice->save();

            DB::commit();

            try {
                if (Settings::get('send_whatsapp') == true) {
                    $whatsapp = app(InvoiceWhatsAppNotifier::class);
                    // 2. رسالة لكل دفعة
                    $paymentMethod = $this->getPaymentMethodText($payment['type']);
                    $whatsapp->sendPaymentReceived($invoice, $payment['payed_amount'], $paymentMethod);
                }
            } catch (\Exception $e) {
                \Log::warning("WhatsApp failed: " . $e->getMessage());
            }

            return redirect()->back()
                ->with('success', 'Payment added successfully! Amount: ' . number_format($request->payed_amount, 2) . ' QAR');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to add payment: ' . $e->getMessage());
        }
    }

    /**
     * ====================================================
     * DELETE PAYMENT
     * ====================================================
     */
    public function deletePayment($payment_id)
    {
        $payment = Payments::findOrFail($payment_id);
        $invoice = $payment->invoice;

        DB::beginTransaction();
        try {
            // Delete cashier transaction
            CashierTransaction::where('payment_id', $payment->id)->delete();

            // Update invoice amounts
            $invoice->paied -= $payment->payed_amount;
            $invoice->remaining += $payment->payed_amount;
            $invoice->save();

            // Delete payment
            $payment->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }

    /**
     * ====================================================
     * HELPER: UPDATE ITEM STATUS
     * ====================================================
     */
    private function updateItemStatus($invoice_id, $selected_items, $status)
    {
        $invoice = Invoice::find($invoice_id);

        // Don't allow item status changes if invoice is delivered or canceled
        if (in_array($invoice->status, ['delivered', 'canceled'])) {
            return; // Skip silently
        }

        if (!empty($selected_items)) {

            // ✅ Process selected items
            $items = InvoiceItems::where('invoice_id', $invoice_id)
                ->whereIn('id', $selected_items)
                ->get();

            foreach ($items as $item) {

                if ($status === 'delivery') {
                    // ✅ Mark as delivery → Deliver item (reduce stock)
                    $item->deliverItem(); // Safe - won't reduce twice

                    $item->status = 'delivery';
                    $item->delivered_at = now();
                    $item->save();

                } else {
                    // ✅ Mark as ready or other status
                    $item->status = $status;
                    $item->save();
                }
            }

            // ✅ Clear status from non-selected items
            $nonSelectedItems = InvoiceItems::where('invoice_id', $invoice_id)
                ->whereNotIn('id', $selected_items)
                ->where('status', $status)
                ->get();

            foreach ($nonSelectedItems as $item) {
                if ($item->status === 'delivery') {
                    // Was delivery, now clearing → Undeliver (return stock)
                    $item->undeliverItem(); // Safe - won't return if not delivered
                }

                $item->status = null;
                $item->delivered_at = null;
                $item->save();
            }

        } else {
            // ✅ Clear all items with this status
            $items = InvoiceItems::where('invoice_id', $invoice_id)
                ->where('status', $status)
                ->get();

            foreach ($items as $item) {
                if ($item->status === 'delivery') {
                    // Was delivery → Undeliver
                    $item->undeliverItem();
                }

                $item->status = null;
                $item->delivered_at = null;
                $item->save();
            }
        }
    }

    protected function getPaymentMethodText($type)
    {
        $methods = [
            'Cash' => 'نقدي',
            'visa' => 'فيزا',
            'Atm' => 'بطاقة',
            'Master Card' => 'ماستر كارد',
            'Bank' => 'تحويل بنكي',
            'other' => 'أخرى'
        ];
        return $methods[$type] ?? $type;
    }
}
