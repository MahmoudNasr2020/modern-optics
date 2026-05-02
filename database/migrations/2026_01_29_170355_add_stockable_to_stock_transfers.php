<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStockableToStockTransfers extends Migration
{
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            // Add polymorphic columns
            if (!Schema::hasColumn('stock_transfers', 'stockable_type')) {
                $table->string('stockable_type')->nullable();
            }

            if (!Schema::hasColumn('stock_transfers', 'stockable_id')) {
                $table->unsignedBigInteger('stockable_id')->nullable();
            }

            // Add index
            $table->index(['stockable_type', 'stockable_id']);

            // Make product_id nullable
            //$table->unsignedBigInteger('product_id')->nullable()->change();
        });

        // Migrate existing data
        DB::statement("
            UPDATE stock_transfers
            SET stockable_type = 'App\\\\Product',
                stockable_id = product_id
            WHERE product_id IS NOT NULL AND stockable_type IS NULL
        ");
    }

    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropIndex(['stockable_type', 'stockable_id']);
            $table->dropColumn(['stockable_type', 'stockable_id']);
        });
    }
}
