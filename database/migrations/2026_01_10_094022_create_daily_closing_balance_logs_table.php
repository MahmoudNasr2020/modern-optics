<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyClosingBalanceLogsTable extends Migration
{

    public function up()
    {
        Schema::create('daily_closing_balance_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_closing_id');
            $table->string('from_payment_type');
            $table->string('to_payment_type');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('daily_closing_id')
                ->references('id')
                ->on('daily_closings')
                ->onDelete('cascade');

            $table->index('daily_closing_id');
        });
    }


    public function down()
    {
        Schema::dropIfExists('daily_closing_balance_logs');
    }
}
