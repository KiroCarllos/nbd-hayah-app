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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('type'); // credit, debit
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->string('reference')->nullable(); // Payment gateway reference
            $table->string('status')->default('completed'); // completed, pending, failed
            $table->json('payment_data')->nullable(); // Store payment gateway response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
