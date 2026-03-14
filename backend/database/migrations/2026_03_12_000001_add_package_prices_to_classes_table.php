<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->decimal('price_1_month', 10, 2)->nullable()->after('room_id');
            $table->decimal('price_3_month', 10, 2)->nullable()->after('price_1_month');
            $table->decimal('price_6_month', 10, 2)->nullable()->after('price_3_month');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['price_1_month', 'price_3_month', 'price_6_month']);
        });
    }
};
