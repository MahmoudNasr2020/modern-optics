<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStockableToStockMovements extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Add polymorphic columns
            $table->string('stockable_type')->nullable()->after('product_id');
            $table->unsignedBigInteger('stockable_id')->nullable()->after('stockable_type');

            // Add index
            $table->index(['stockable_type', 'stockable_id']);
        });

        // Migrate existing data
        DB::statement("
            UPDATE stock_movements
            SET stockable_type = 'App\\\\Product',
                stockable_id = (SELECT id FROM products WHERE products.product_id = stock_movements.product_id)
            WHERE product_id IS NOT NULL
        ");
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['stockable_type', 'stockable_id']);
            $table->dropColumn(['stockable_type', 'stockable_id']);
        });
    }
}
