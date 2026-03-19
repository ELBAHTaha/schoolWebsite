<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pre_registration_leads', function (Blueprint $table) {
            $table->enum('payment_method', ['onsite', 'online'])->default('onsite')->after('message');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending')->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('pre_registration_leads', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status']);
        });
    }
};
