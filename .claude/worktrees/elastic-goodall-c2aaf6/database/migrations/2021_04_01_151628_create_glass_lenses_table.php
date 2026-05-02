<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlassLensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glass_lenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_id');
            $table->string('brand');
            $table->string('frame_type');
            $table->string('lense_type');
            $table->string('lense_production');
            $table->string('index');
            $table->string('life_style');
            $table->string('customer_activity');
            $table->string('lense_tech');
            $table->string('description');
            $table->float('price');
            $table->float('retail_price');
            $table->integer('amount');
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
        Schema::dropIfExists('glass_lenses');
    }
}
