<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('target_role')->constrained('classes')->nullOnDelete();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('deadline');
        });

        DB::table('assignments')->update(['due_date' => DB::raw('deadline')]);
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_id');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
};
