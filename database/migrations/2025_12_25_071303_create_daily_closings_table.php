<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyClosingsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_closings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->unique();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_closings');
    }
}
