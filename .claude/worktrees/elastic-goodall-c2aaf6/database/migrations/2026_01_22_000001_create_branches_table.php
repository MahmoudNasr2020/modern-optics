<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100); // Main Branch
            $table->string('name_ar', 100)->nullable(); // الفرع الرئيسي
            $table->string('code', 20)->unique(); // BR-001
            $table->string('address', 255);
            $table->string('city', 50)->nullable(); // المدينة
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('manager_name', 100)->nullable(); // مدير الفرع
            $table->text('description')->nullable(); // وصف الفرع

            // Status
            $table->boolean('is_active')->default(true); // نشط/غير نشط
            $table->boolean('is_main')->default(false); // هل هو الفرع الرئيسي؟

            // Working Hours
            $table->time('opening_time')->nullable(); // وقت الفتح
            $table->time('closing_time')->nullable(); // وقت الإغلاق

            // Statistics (optional - للأداء)
            $table->decimal('total_sales', 15, 2)->default(0); // إجمالي المبيعات
            $table->integer('total_invoices')->default(0); // عدد الفواتير

            $table->timestamps();
            $table->softDeletes(); // للحذف الآمن

            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index('is_main');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
