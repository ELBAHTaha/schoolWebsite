<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('classes', 'school_year_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropConstrainedForeignId('school_year_id');
            });
        }

        Schema::dropIfExists('school_years');
    }

    public function down(): void
    {
        if (! Schema::hasTable('school_years')) {
            Schema::create('school_years', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->date('starts_at');
                $table->date('ends_at');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('classes', 'school_year_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->foreignId('school_year_id')->nullable()->after('room_id')->constrained('school_years')->nullOnDelete();
            });
        }
    }
};
