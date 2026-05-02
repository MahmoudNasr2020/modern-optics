<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{

    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer_in', 'transfer_out', 'sale', 'return','reserve']);
            $table->integer('quantity');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('reference_type')->nullable(); // StockTransfer, Invoice, etc
            $table->unsignedInteger('reference_id')->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['branch_id', 'product_id']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
