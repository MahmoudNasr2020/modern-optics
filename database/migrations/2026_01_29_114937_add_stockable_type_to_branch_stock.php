<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStockableTypeToBranchStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_stock', function (Blueprint $table) {
            // Add polymorphic columns
            $table->string('stockable_type')->nullable()->after('product_id');
            $table->unsignedBigInteger('stockable_id')->nullable()->after('stockable_type');

            // Add index
            $table->index(['stockable_type', 'stockable_id']);
        });

        // Migrate existing data
        DB::table('branch_stock')->whereNotNull('product_id')->update([
            'stockable_type' => 'App\\Product',
            'stockable_id' => DB::raw('(SELECT id FROM products WHERE products.id = branch_stock.product_id)')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_stock', function (Blueprint $table) {
            $table->dropIndex(['stockable_type', 'stockable_id']);
            $table->dropColumn(['stockable_type', 'stockable_id']);
        });
    }
}
