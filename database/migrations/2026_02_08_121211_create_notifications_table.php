<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            // الجهة المستقبلة
            $table->unsignedBigInteger('user_id')->nullable(); // إشعار لمستخدم معين
            $table->unsignedBigInteger('branch_id')->nullable(); // إشعار لفرع معين
            $table->string('role')->nullable(); // إشعار لدور معين (super-admin, manager, etc.)

            // محتوى الإشعار
            $table->string('type'); // invoice_created, transfer_created, low_stock, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // بيانات إضافية

            // الجهة المرسلة
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('icon')->default('bell'); // أيقونة الإشعار
            $table->string('color')->default('info'); // info, success, warning, danger

            // الحالة
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            // الإجراء
            $table->string('action_url')->nullable(); // رابط الإشعار

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('branch_id');
            $table->index('role');
            $table->index('is_read');
            $table->index('created_at');

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
