<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('storage_limit')->default(5368709120)->after('email_verified_at');
            $table->unsignedBigInteger('storage_used')->default(0)->after('storage_limit');
            $table->string('stripe_price')->nullable()->after('remember_token');
            $table->string('stripe_subscription')->nullable()->after('stripe_price');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['storage_limit', 'storage_used', 'stripe_price', 'stripe_subscription']);
        });
    }
};
