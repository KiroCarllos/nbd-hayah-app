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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->unique()->after('email');
            $table->string('profile_image')->nullable()->after('mobile');
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('profile_image');
            $table->boolean('is_admin')->default(false)->after('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'profile_image', 'wallet_balance', 'is_admin']);
        });
    }
};
