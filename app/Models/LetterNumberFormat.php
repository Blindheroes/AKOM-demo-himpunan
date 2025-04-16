<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterNumberFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'format_pattern',
        'next_number',
        'reset_period',
        'is_active',
    ];

    protected $casts = [
        'next_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
