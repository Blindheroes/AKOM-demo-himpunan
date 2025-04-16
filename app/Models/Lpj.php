<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lpj extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lpjs';

    protected $fillable = [
        'title',
        'event_id',
        'template_id',
        'content',
        'status',
        'document_path',
        'version',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'content' => 'array',
        'version' => 'integer',
        'approval_date' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(LpjTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'lpj_id');
    }
}
