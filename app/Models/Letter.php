<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Letter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'template_id',
        'letter_number',
        'date',
        'regarding',
        'recipient',
        'recipient_position',
        'recipient_institution',
        'content',
        'attachment',
        'status',
        'document_path',
        'version',
        'department_id',
        'created_by',
        'signed_by',
    ];

    protected $casts = [
        'date' => 'date',
        'signing_date' => 'datetime',
        'version' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(LetterTemplate::class, 'template_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
