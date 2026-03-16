<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','directeur','secretary','professor','student','visitor','commercial') DEFAULT 'visitor'");
        DB::statement("ALTER TABLE announcements MODIFY COLUMN target_role ENUM('admin','directeur','secretary','professor','student','visitor','commercial') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','secretary','professor','student','visitor') DEFAULT 'visitor'");
        DB::statement("ALTER TABLE announcements MODIFY COLUMN target_role ENUM('admin','secretary','professor','student','visitor') NULL");
    }
};
