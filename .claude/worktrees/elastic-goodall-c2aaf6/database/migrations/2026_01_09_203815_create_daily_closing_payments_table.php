<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyClosingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_closing_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_closing_id');
            $table->enum('payment_type', ['CASH', 'VISA', 'MASTERCARD', 'ATM', 'POINT', 'GIFT VOUCHER']);
            $table->decimal('system_amount', 10, 2)->default(0);
            $table->decimal('entry_amount', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);
            $table->enum('reason', ['ACTUAL_AVERAGE', 'WRONG_PAYMENT', 'ACTUAL_SHORTAGE'])->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_cleared')->default(0);
            $table->timestamps();

            $table->foreign('daily_closing_id')
                ->references('id')
                ->on('daily_closings')
                ->onDelete('cascade');

            $table->unique(['daily_closing_id', 'payment_type']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_closing_payments');
    }
}
