<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreRegistrationLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'desired_program',
        'message',
        'status',
        'assigned_commercial_id',
    ];

    public function assignedCommercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_commercial_id');
    }
}
