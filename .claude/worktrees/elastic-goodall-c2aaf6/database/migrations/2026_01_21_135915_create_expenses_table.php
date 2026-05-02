<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');

            // التصنيف
            $table->unsignedBigInteger('category_id');

            // المبلغ
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('QAR');

            // التاريخ والدفع
            $table->date('expense_date');
            $table->enum('payment_method', ['CASH', 'VISA','MASTERCARD', 'MADA', 'ATM', 'BANK_TRANSFER', 'GIFT VOUCHER','POINT']);

            // من دفع؟
            $table->unsignedBigInteger('paid_by')->nullable();

            // تفاصيل
            $table->string('vendor_name')->nullable(); // اسم المورد
            $table->string('receipt_number')->nullable(); // رقم الإيصال
            $table->text('description');
            $table->text('notes')->nullable();

            // المرفقات
            $table->string('receipt_file')->nullable();

            // الحالة
            /*$table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();*/

            // ربط بالكاشير
            $table->boolean('deducted_from_cashier')->default(false);
            $table->unsignedBigInteger('cashier_transaction_id')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cashier_transaction_id')->references('id')->on('cashier_transactions')->onDelete('set null');

            // Indexes
            $table->index('expense_date');
            $table->index('category_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
