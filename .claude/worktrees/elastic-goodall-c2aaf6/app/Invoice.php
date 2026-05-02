<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{

    protected $table = 'invoices';

    protected $guarded = ['id'];

    protected $casts = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id');
    }

   /* public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }*/

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'code', 'doctor_id');
    }


    public function insurance()
    {
        return $this->belongsTo(InsuranceCompany::class,'insurance_id');
    }
    public function cardholder()
    {
        return $this->belongsTo(Cardholder::class,'cardholder_id');
    }

    public function payments(){
        return $this->hasMany(Payments::class,'invoice_id');
    }


    /**
     * Mark invoice as delivered
     */
    public function markAsDelivered()
    {
        if ($this->status === 'delivered') {
            throw new \Exception('Invoice already delivered');
        }

        if ($this->remaining > 0) {
            throw new \Exception('Cannot deliver invoice with remaining balance');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $this->status;

            // ✅ Deliver all items (safe - won't reduce if already delivered)
            foreach ($this->invoiceItems as $item) {
                $item->deliverItem(); // Only reduces if not already delivered

                // Update item status
                $item->status = 'delivery';
                $item->delivered_at = now();
                $item->save();
            }

            // Update invoice
            $this->status = 'delivered';
            $this->delivered_at = now();
            $this->delivered_by = auth()->user()->id;
            $this->save();

            DB::commit();

            \Log::info("Invoice {$this->invoice_code} delivered (was: {$oldStatus})");

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to deliver invoice {$this->invoice_code}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel invoice and handle stock
     */
    public function cancelInvoice($reason = null, $notes = null)
    {
        if ($this->status === 'canceled') {
            throw new \Exception('Invoice already canceled');
        }

        DB::beginTransaction();
        try {
            $originalStatus = $this->status;

            // Update invoice
            $this->status = 'canceled';
            $this->return_reason = $reason;
            $this->notes = $notes;
            $this->canceled_at = now();
            $this->canceled_by = auth()->user()->id;
            $this->paied = 0;
            $this->remaining = 0;
            $this->save();

            // Handle stock based on original status
            foreach ($this->invoiceItems as $item) {
                if (in_array($originalStatus, ['pending', 'ready', 'Under Process'])) {
                    // Was not delivered → Unreserve
                    $item->unreserveStock();
                } elseif ($originalStatus === 'delivered') {
                    // Was delivered → Return stock
                    $item->returnStock();
                }

                // Mark item as canceled
                $item->cancelItem($reason);
            }

            // Process payment refunds
            $this->processRefunds($reason);

            DB::commit();

            \Log::info("Invoice {$this->invoice_code} canceled (was: {$originalStatus})");

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Failed to cancel invoice {$this->invoice_code}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process payment refunds
     */
    protected function processRefunds($reason = null)
    {
        $originalPayments = Payments::where('invoice_id', $this->id)
            ->where(function($query) {
                $query->where('is_refund', false)
                    ->orWhereNull('is_refund');
            })
            ->get();

        foreach ($originalPayments as $payment) {

            // Create refund payment
            $refundPayment = Payments::create([
                'invoice_id' => $this->id,
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

            // Create cashier transaction
            CashierTransaction::create([
                'transaction_type' => 'refund',
                'payment_type' => $payment->type,
                'amount' => -abs($payment->payed_amount),
                'currency' => $payment->currency ?? 'QAR',
                'exchange_rate' => $payment->exchange_rate ?? 1,
                'amount_in_sar' => -abs($payment->payed_amount),
                'invoice_id' => $this->id,
                'payment_id' => $refundPayment->id,
                'customer_id' => $this->customer_id,
                'bank' => $payment->bank,
                'card_number' => $payment->card_number,
                'notes' => "Refund for invoice #{$this->invoice_code}" . ($reason ? " - {$reason}" : ""),
                'cashier_id' => auth()->user()->id,
                'transaction_date' => now(),
            ]);
        }
    }


}
