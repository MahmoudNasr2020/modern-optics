<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoTypeToLensPurchaseOrders extends Migration
{
    public function up()
    {
        Schema::table('lens_purchase_orders', function (Blueprint $table) {
            $table->string('po_type', 20)->default('lens')->after('po_number')
                  ->comment('lens | contact_lens');
        });
    }

    public function down()
    {
        Schema::table('lens_purchase_orders', function (Blueprint $table) {
            $table->dropColumn('po_type');
        });
    }
}
