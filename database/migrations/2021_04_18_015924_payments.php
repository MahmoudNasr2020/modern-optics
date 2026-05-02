<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_id');
            $table->string('type');
            $table->string('bank')->nullable();
            $table->string('card_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('currency')->nullable();
            $table->float('payed_amount');
            $table->integer('exchange_rate')->nullable();
            $table->float('local_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
