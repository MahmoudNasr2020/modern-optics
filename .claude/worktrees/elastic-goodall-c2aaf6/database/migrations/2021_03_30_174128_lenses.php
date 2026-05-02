<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Lenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenses', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('customer_id');
            $table->String('doctor_id');
            $table->string('invoice_id');
            $table->date('visit_date')->nullable();
            $table->string('sph_right_sign')->nullable();
            $table->float('sph_right_value')->nullable();
            $table->string('cyl_right_sign')->nullable();
            $table->float('cyl_right_value')->nullable();

            $table->float('axis_right')->nullable();
            $table->float('addition_right')->nullable();

            $table->string('pd_right')->nullable();
            $table->string('sph_left_sign')->nullable();
            $table->float('sph_left_value')->nullable();
            $table->string('cyl_left_sign')->nullable();
            $table->float('cyl_left_value')->nullable();

            $table->float('axis_left')->nullable();
            $table->float('addition_left')->nullable();
            $table->float('pd_left')->nullable();

            $table->string('right_diagnosis')->nullable();
            $table->string('left_diagnosis')->nullable();
            $table->string('glasses')->nullable();

            $table->string('attachment')->nullable();

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
        Schema::dropIfExists('lenses');
    }
}
