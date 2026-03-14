<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'class_id',
        'account_balance',
        'payment_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'account_balance' => 'decimal:2',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function taughtClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'professor_id');
    }

    public function enrolledClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'student_class', 'student_id', 'class_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->enrolledClasses();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function uploadedPdfs(): HasMany
    {
        return $this->hasMany(Pdf::class, 'uploaded_by');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'professor_id');
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'professor_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'professor_id');
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(ProfessorWorkingHour::class, 'professor_id');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}
