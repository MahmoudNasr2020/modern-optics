<?php

namespace App\Http\Controllers\Dashboard;

use App\InvoiceItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InvoiceItemsController extends Controller
{
    /**
     * Update item status to 'ready'
     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $invoiceItem = InvoiceItems::findOrFail($id);
            $invoice = $invoiceItem->invoice;

            // Don't allow changes if invoice is delivered or canceled
            if (in_array($invoice->status, ['delivered', 'canceled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update item status for delivered/canceled invoices'
                ], 400);
            }

            if ($request->status == 'checked') {
                // ✅ Mark as ready (no stock changes)
                $invoiceItem->update(['status' => 'ready']);

            } else {
                // ✅ Unmark (no stock changes)
                $invoiceItem->update(['status' => null]);
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to update item status: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update item delivery status (WITH STOCK MANAGEMENT)
     */
    public function updateDeliveryStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $invoiceItem = InvoiceItems::findOrFail($id);
            $invoice = $invoiceItem->invoice;

            // Don't allow changes if invoice is delivered or canceled
            if (in_array($invoice->status, ['delivered', 'canceled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update item status for delivered/canceled invoices'
                ], 400);
            }

            if ($request->status == 'delivery') {
                // ✅ Mark as delivery → Reduce stock
                $invoiceItem->deliverItem(); // Safe - won't reduce twice

                $invoiceItem->status = 'delivery';
                $invoiceItem->delivered_at = Carbon::now();
                $invoiceItem->save();

                \Log::info("Item {$id} marked as delivery - stock reduced");

            } else {
                // ✅ Unmark delivery → Return stock
                $invoiceItem->undeliverItem(); // Safe - only returns if was delivered

                $invoiceItem->status = null;
                $invoiceItem->delivered_at = null;
                $invoiceItem->save();

                \Log::info("Item {$id} unmarked from delivery - stock returned");
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to update delivery status for item {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
