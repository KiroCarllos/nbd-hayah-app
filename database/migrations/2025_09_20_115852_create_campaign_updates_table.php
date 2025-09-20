<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('title'); // عنوان التحديث
            $table->text('content'); // محتوى التحديث
            $table->string('type')->default('general'); // نوع التحديث: general, medical, financial, etc.
            $table->json('images')->nullable(); // صور التحديث (اختيارية)
            $table->boolean('is_important')->default(false); // هل التحديث مهم
            $table->foreignId('created_by')->constrained('users'); // من أضاف التحديث
            $table->timestamps();

            // إضافة فهرس للبحث السريع
            $table->index(['campaign_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_updates');
    }
};
