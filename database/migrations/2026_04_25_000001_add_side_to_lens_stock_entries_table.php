<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSideToLensStockEntriesTable extends Migration
{
    public function up()
    {
        // If the table doesn't exist yet, create it with side included
        if (!Schema::hasTable('lens_stock_entries')) {
            Schema::create('lens_stock_entries', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('branch_id')->nullable();
                $table->unsignedInteger('glass_lense_id')->nullable();
                $table->enum('side', ['R', 'L'])->nullable()->comment('Eye side: R=Right, L=Left');
                $table->enum('direction', ['in', 'out']);
                $table->unsignedInteger('quantity')->default(1);
                $table->string('source_type')->nullable();
                $table->unsignedInteger('source_id')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedInteger('user_id')->nullable();
                $table->timestamps();
            });
            return;
        }

        // Table already exists — just add the side column if missing
        if (!Schema::hasColumn('lens_stock_entries', 'side')) {
            Schema::table('lens_stock_entries', function (Blueprint $table) {
                $table->enum('side', ['R', 'L'])->nullable()
                      ->after('glass_lense_id')
                      ->comment('Eye side: R=Right, L=Left');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('lens_stock_entries', 'side')) {
            Schema::table('lens_stock_entries', function (Blueprint $table) {
                $table->dropColumn('side');
            });
        }
    }
}
