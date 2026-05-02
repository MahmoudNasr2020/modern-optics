<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceCompanyCategoryDiscountTable extends Migration
{

    public function up()
    {
        Schema::create('insurance_company_category_discount', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('insurance_company_id');
            $table->foreign('insurance_company_id')->references('id')->on('insurance_companies')->onDelete('cascade');

            $table->decimal('discount_percent');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('insurance_company_category_discount');
    }
}
