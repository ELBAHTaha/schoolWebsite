<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'professor_id',
        'room_id',
        'price_1_month',
        'price_3_month',
        'price_6_month',
    ];

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_class', 'class_id', 'student_id');
    }

    public function primaryStudents(): HasMany
    {
        return $this->hasMany(User::class, 'class_id');
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function pdfs(): HasMany
    {
        return $this->hasMany(Pdf::class, 'class_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

}
