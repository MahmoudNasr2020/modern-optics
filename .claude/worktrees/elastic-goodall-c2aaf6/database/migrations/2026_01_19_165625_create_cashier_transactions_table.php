<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashierTransactionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cashier_transactions')) {
        Schema::create('cashier_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->enum('transaction_type', [
                'sale',
                'refund',
                'expense',
                'deposit',
                'withdrawal'
            ]);

            $table->enum('payment_type', ['CASH', 'VISA','MASTERCARD', 'MADA', 'ATM', 'BANK_TRANSFER', 'GIFT VOUCHER','POINT']);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('QAR');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->decimal('amount_in_sar', 10, 2);

            // ✔️ التصحيح هنا
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('bank')->nullable();
            $table->string('card_number')->nullable();
            $table->string('cheque_number')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('cashier_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->timestamp('transaction_date');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('payment_type');
            $table->index('cashier_id');
        });

    }
    }

    public function down()
    {
        Schema::dropIfExists('cashier_transactions');
    }
}
