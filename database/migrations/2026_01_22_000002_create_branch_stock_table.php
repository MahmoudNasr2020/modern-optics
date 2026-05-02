<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchStockTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch_stock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');

            // Quantities
            $table->integer('quantity')->default(0); // الكمية المتاحة
            $table->integer('min_quantity')->default(5); // الحد الأدنى (للتنبيه)
            $table->integer('max_quantity')->default(100); // الحد الأقصى
            $table->integer('reserved_quantity')->default(0); // محجوز (للطلبات قيد التنفيذ)

            // Cost Tracking (optional)
            $table->decimal('average_cost', 10, 2)->nullable(); // متوسط التكلفة
            $table->decimal('last_cost', 10, 2)->nullable(); // آخر تكلفة شراء

            // Stock History
            $table->integer('total_in')->default(0); // إجمالي الوارد
            $table->integer('total_out')->default(0); // إجمالي الصادر

            $table->timestamps();

            // Foreign Keys
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');


            // Unique Constraint - منع التكرار
            $table->unique(['branch_id', 'product_id'], 'branch_product_unique');

            // Indexes للأداء
            $table->index('branch_id');
            $table->index('product_id');
            $table->index('quantity'); // للبحث عن المنتجات المتاحة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_stock');
    }
};
