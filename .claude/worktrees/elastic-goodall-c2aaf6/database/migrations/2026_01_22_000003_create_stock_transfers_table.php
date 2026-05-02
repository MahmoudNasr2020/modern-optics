<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transfer_number', 50)->unique(); // ST-0001

            // From/To Branches
            $table->unsignedBigInteger('from_branch_id');
            $table->unsignedBigInteger('to_branch_id');

            // Product & Quantity
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');

            // Dates
            $table->date('transfer_date'); // تاريخ الطلب
            $table->date('approved_date')->nullable(); // تاريخ الموافقة
            $table->date('shipped_date')->nullable(); // تاريخ الشحن
            $table->date('received_date')->nullable(); // تاريخ الاستلام

            // Status
            $table->enum('status', [
                'pending',      // معلق (في انتظار الموافقة)
                'approved',     // تمت الموافقة
                'in_transit',   // في الطريق
                'received',     // تم الاستلام
                'canceled'      // ملغي
            ])->default('pending');

            // Users
            $table->unsignedBigInteger('created_by'); // من طلب النقل
            $table->unsignedBigInteger('approved_by')->nullable(); // من وافق
            $table->unsignedBigInteger('shipped_by')->nullable(); // من شحن
            $table->unsignedBigInteger('received_by')->nullable(); // من استلم

            // Notes & Reasons
            $table->text('notes')->nullable(); // ملاحظات
            $table->text('rejection_reason')->nullable(); // سبب الرفض

            // Priority (optional)
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('from_branch_id')->references('id')->on('branches')->onDelete('cascade');;
            $table->foreign('to_branch_id')->references('id')->on('branches')->onDelete('cascade');;
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');;
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('shipped_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('received_by')->references('id')->on('users')->onDelete('cascade');;

            // Indexes
            $table->index('transfer_number');
            $table->index(['transfer_date', 'status']);
            $table->index('status');
            $table->index('from_branch_id');
            $table->index('to_branch_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
