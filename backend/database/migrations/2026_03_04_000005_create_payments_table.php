<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->enum('status', ['paid', 'unpaid', 'late'])->default('unpaid');
            $table->enum('payment_method', ['cmi', 'cash'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
