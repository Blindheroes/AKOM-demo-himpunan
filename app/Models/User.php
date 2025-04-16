<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Lpj;
use App\Models\News;
use App\Models\Event;
use App\Models\Letter;
use App\Models\Gallery;
use App\Models\Document;
use App\Models\Signature;
use App\Models\Department;
use App\Models\LpjTemplate;
use App\Models\OAuthProvider;
use App\Models\LetterTemplate;
use App\Models\EventRegistration;
use Laravel\Sanctum\HasApiTokens;
use App\Models\FinancialTransaction;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'phone',
        'address',
        'profile_photo_path',
        'role',
        'department_id',
        'position',
        'join_date',
        'is_active',
        'signature_authority',
        'notification_preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'is_active' => 'boolean',
            'signature_authority' => 'boolean',
            'notification_preferences' => 'array',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function headOfDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'head_id');
    }

    public function oauthProviders(): HasMany
    {
        return $this->hasMany(OAuthProvider::class);
    }

    public function signature(): HasOne
    {
        return $this->hasOne(Signature::class);
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function organizingEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function approvedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'approved_by');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function createdLpjTemplates(): HasMany
    {
        return $this->hasMany(LpjTemplate::class, 'created_by');
    }

    public function createdLpjs(): HasMany
    {
        return $this->hasMany(Lpj::class, 'created_by');
    }

    public function approvedLpjs(): HasMany
    {
        return $this->hasMany(Lpj::class, 'approved_by');
    }

    public function createdLetterTemplates(): HasMany
    {
        return $this->hasMany(LetterTemplate::class, 'created_by');
    }

    public function createdLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'created_by');
    }

    public function signedLetters(): HasMany
    {
        return $this->hasMany(Letter::class, 'signed_by');
    }

    public function authoredNews(): HasMany
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function approvedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'approved_by');
    }

    public function recordedFinancialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'recorded_by');
    }

    public function approvedFinancialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'approved_by');
    }

    public function createdGalleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'created_by');
    }
}
