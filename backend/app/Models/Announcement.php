<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'target_role',
        'class_id',
        'start_date',
        'end_date',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function scopeActive($query, ?string $date = null)
    {
        $date = $date ?: now()->toDateString();

        return $query
            ->where(function ($q) use ($date) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $date);
            })
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $date);
            });
    }
}
