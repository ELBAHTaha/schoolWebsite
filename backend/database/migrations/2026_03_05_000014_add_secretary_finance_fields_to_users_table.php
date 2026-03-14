<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('phone')->constrained('classes')->nullOnDelete();
            $table->decimal('account_balance', 10, 2)->default(0)->after('class_id');
            $table->enum('payment_status', ['paid', 'pending', 'late'])->default('pending')->after('account_balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_id');
            $table->dropColumn(['account_balance', 'payment_status']);
        });
    }
};
