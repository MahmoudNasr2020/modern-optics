<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{

    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_code');
            $table->integer('customer_id');
            $table->integer('doctor_id');
            $table->integer('user_id');
            $table->date('pickup_date');
            $table->string('status')->default('pending');
            $table->string('payment_way');
            $table->float('paied');
            $table->float('remaining');
            $table->float('total');
            $table->text('notes')->nullable();
            $table->string('tray_number')->nullable();
            $table->string('return_reason')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_value')->nullable();
            $table->integer('insurance_id')->nullable();
            $table->json('insurance_approval_amount')->default(0);
            $table->integer('cardholder_id')->nullable();
            $table->json('cardholder_discounts')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            //$table->foreign('canceled_by')->nullable();


            $table->foreign('delivered_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('canceled_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cardholder_id')->references('id')->on('cardholders')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
