<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardholderCategoryDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cardholder_category_discount', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('cardholder_id');
            $table->foreign('cardholder_id')->references('id')->on('cardholders')->onDelete('cascade');

            $table->decimal('discount_percent');
            $table->timestamps();
        });

    }


    public function down()
    {
        Schema::dropIfExists('cardholder_category_discount');
    }
}
