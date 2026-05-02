<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // إضافة branch_id
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');

            // Foreign key
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('restrict');

            // Index للسرعة
            $table->index('branch_id');
        });

        Schema::table('expense_categories', function (Blueprint $table) {

            $table->unsignedBigInteger('branch_id')->nullable()->after('id');

            // Foreign key
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('restrict');

            // Index
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
}
