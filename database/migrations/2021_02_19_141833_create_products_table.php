<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id');
            $table->integer('branch_id');
            $table->integer('brand_id');
            $table->integer('model_id');
            $table->string('product_id');
            $table->string('color');
            $table->string('size');
            $table->string('description');
            $table->float('price');
            $table->float('retail_price');
            $table->float('tax');
            $table->float('total');
            $table->integer('amount')->default(0);
            $table->string('discount_type')->nullable();
            $table->float('discount_value')->nullable();
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
        Schema::dropIfExists('products');
    }
}
