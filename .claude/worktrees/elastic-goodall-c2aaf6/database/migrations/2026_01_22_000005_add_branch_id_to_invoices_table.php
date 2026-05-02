<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // إضافة branch_id للفواتير
            $table->unsignedBigInteger('branch_id')->nullable()->after('customer_id');

            // Foreign Key
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches')
                  ->onDelete('restrict'); // منع حذف الفرع لو فيه فواتير

            // Index
            $table->index('branch_id');
            $table->index(['branch_id', 'created_at']); // للتقارير
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['branch_id', 'created_at']);
            $table->dropColumn('branch_id');
        });
    }
};
