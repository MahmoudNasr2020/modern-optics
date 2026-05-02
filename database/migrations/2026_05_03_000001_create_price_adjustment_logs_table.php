<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceAdjustmentLogsTable extends Migration
{
    public function up()
    {
        Schema::create('price_adjustment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 10);                   // increase | decrease
            $table->decimal('percent', 8, 2);             // e.g. 12.50
            $table->json('apply_to');                     // ["products","lenses"]
            $table->unsignedInteger('products_affected')->default(0);
            $table->unsignedInteger('lenses_affected')->default(0);
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_adjustment_logs');
    }
}
