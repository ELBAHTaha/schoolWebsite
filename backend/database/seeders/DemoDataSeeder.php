<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Pdf;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@jefalprive.ma'], [
            'name' => 'Admin JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'phone' => '+212600000001',
        ]);

        User::updateOrCreate(['email' => 'directeur@jefalprive.ma'], [
            'name' => 'Directeur JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'directeur',
            'phone' => '+212600000006',
        ]);

        $secretary = User::updateOrCreate(['email' => 'secretary@jefalprive.ma'], [
            'name' => 'Secretaire JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'secretary',
            'phone' => '+212600000002',
        ]);

        $professor = User::updateOrCreate(['email' => 'professor@jefalprive.ma'], [
            'name' => 'Professeur JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'professor',
            'phone' => '+212600000003',
        ]);

        $student = User::updateOrCreate(['email' => 'student@jefalprive.ma'], [
            'name' => 'Etudiant JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'student',
            'phone' => '+212600000004',
        ]);

        User::updateOrCreate(['email' => 'visitor@jefalprive.ma'], [
            'name' => 'Visiteur JEFAL',
            'password' => Hash::make('Password123!'),
            'role' => 'visitor',
            'phone' => '+212600000005',
        ]);

        User::updateOrCreate(['email' => 'commercial1@jefalprive.ma'], [
            'name' => 'Commercial JEFAL 1',
            'password' => Hash::make('Password123!'),
            'role' => 'commercial',
            'phone' => '+212600000007',
        ]);

        User::updateOrCreate(['email' => 'commercial2@jefalprive.ma'], [
            'name' => 'Commercial JEFAL 2',
            'password' => Hash::make('Password123!'),
            'role' => 'commercial',
            'phone' => '+212600000008',
        ]);

        $room = Room::updateOrCreate(['name' => 'Salle A1'], [
            'name' => 'Salle A1',
            'capacity' => 25,
        ]);

        $class = SchoolClass::updateOrCreate(['name' => 'Francais A2'], [
            'name' => 'Francais A2',
            'description' => 'Niveau intermediaire de francais.',
            'professor_id' => $professor->id,
            'room_id' => $room->id,
            'price_1_month' => 500,
            'price_3_month' => 1300,
            'price_6_month' => 2400,
        ]);

        $class->students()->syncWithoutDetaching([$student->id]);
        $student->update(['class_id' => $class->id, 'account_balance' => 1200, 'payment_status' => 'pending']);

        Payment::updateOrCreate([
            'student_id' => $student->id,
            'month' => (int) now()->format('n'),
            'year' => (int) now()->format('Y'),
        ], [
            'amount' => 1200,
            'status' => 'unpaid',
            'payment_method' => 'cash',
        ]);

        Announcement::firstOrCreate([
            'title' => 'Bienvenue chez JEFAL Prive',
        ], [
            'content' => 'Inscriptions ouvertes pour la nouvelle session.',
            'created_by' => $admin->id,
            'target_role' => null,
        ]);

        Pdf::firstOrCreate([
            'class_id' => $class->id,
            'title' => 'Plan de cours',
        ], [
            'file_path' => 'pdfs/sample-plan.pdf',
            'uploaded_by' => $professor->id,
        ]);

        Material::firstOrCreate([
            'class_id' => $class->id,
            'title' => 'Plan de cours PDF',
        ], [
            'file_path' => 'materials/sample-plan.pdf',
            'professor_id' => $professor->id,
        ]);
    }
}
