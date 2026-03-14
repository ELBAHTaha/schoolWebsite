<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessorWorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'day_of_week',
        'starts_at',
        'ends_at',
    ];

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
