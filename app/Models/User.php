<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Basic Auth
        'username',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        
        // Employment Status (NEW)
        'current_employment_status',
        'has_side_business',
        'profile_photo_url',
        
        // Core Personal Information
        'birth_date',
        'birth_city',
        'religion',
        
        // Core Contact
        'phone_number',
        'mobile_number',
        'whatsapp',
        
        // Core Student Info
        'NIS',
        'Student_Year',
        'Major',
        'Is_Graduate',
        'CGPA',
        
        // JSON Fields for Extended Data
        'personal_data',    // gender, addresses, citizenship, passport, line, facebook, twitter, instagram, etc.
        'academic_data',    // Edu_Level, Previous_School_Name, School_City, Academic_Advisor, certificates, final projects
        'father_data',      // All father information
        'mother_data',      // All mother information
        'graduation_data',  // Official_Email, Current_Status, Class_Semester, Form_No, Start_Date, End_Date, business info
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
            'is_active' => 'boolean',
            'Is_Graduate' => 'boolean',
            'birth_date' => 'date',
            'CGPA' => 'decimal:2',
            'has_side_business' => 'boolean',
            // JSON fields - automatically encode/decode
            'personal_data' => 'array',
            'academic_data' => 'array',
            'father_data' => 'array',
            'mother_data' => 'array',
            'graduation_data' => 'array',
        ];
    }

    /**
     * Get all businesses owned by this user
     */
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    /**
     * Alias for ownedBusinesses
     */
    public function ownedBusinesses(): HasMany
    {
        return $this->businesses();
    }

    /**
     * Get all businesses user is involved in (any role)
     * Uses user_businesses_details pivot table
     */
    public function involvedBusinesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'user_businesses_details')
                    ->withPivot([
                        'role_type',
                        'Position_name',
                        'Working_Date',
                        'Company_Description',
                        'Income',
                        'end_date',
                        'is_current'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get current active business roles
     */
    public function currentEmployments(): BelongsToMany
    {
        return $this->involvedBusinesses()->wherePivot('is_current', true);
    }

    /**
     * Get businesses where user is employee
     */
    public function employments(): BelongsToMany
    {
        return $this->involvedBusinesses()
                    ->wherePivot('role_type', 'employee')
                    ->wherePivot('is_current', true);
    }

    /**
     * Get user's employment details (direct pivot access)
     */
    public function employmentDetails(): HasMany
    {
        return $this->hasMany(User_Businesses_Detail::class);
    }

    /**
     * Get current employment details
     */
    public function currentEmploymentDetails(): HasMany
    {
        return $this->employmentDetails()->where('is_current', true);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student' && $this->is_active;
    }

    /**
     * Check if user is alumni
     */
    public function isAlumni(): bool
    {
        return $this->role === 'alumni' || !$this->is_active;
    }

    /**
     * Check if user is entrepreneur
     */
    public function isEntrepreneur(): bool
    {
        return $this->current_employment_status === 'entrepreneur';
    }

    /**
     * Check if user has side business
     */
    public function hasSideBusiness(): bool
    {
        return $this->has_side_business === true;
    }

    /**
     * Get total businesses count (owned + involved)
     */
    public function totalBusinessesCount(): int
    {
        return $this->ownedBusinesses()->count() + 
               $this->currentEmployments()->count();
    }

    /**
     * Check if user is intrapreneur (working but has side business)
     */
    public function isIntrapreneur(): bool
    {
        return $this->current_employment_status === 'employed_intrapreneur' && 
               $this->has_side_business === true;
    }

    /**
     * Check if user has any business
     */
    public function hasBusiness(): bool
    {
        return $this->businesses()->exists();
    }

    /**
     * Get total businesses owned count
     */
    public function totalBusinesses(): int
    {
        return $this->businesses()->count();
    }

    /**
     * Check if user has multiple businesses
     */
    public function hasMultipleBusinesses(): bool
    {
        return $this->totalBusinesses() > 1;
    }

    /**
     * Get employment status label in Indonesian
     */
    public function getEmploymentStatusLabel(): string
    {
        return match($this->current_employment_status) {
            'employed_intrapreneur' => 'Bekerja sebagai Profesional',
            'entrepreneur' => 'Entrepreneur (Pemilik Bisnis)',
            'job_seeking' => 'Mencari Pekerjaan',
            'preparing_business' => 'Persiapan Entrepreneur',
            default => 'Belum Diisi'
        };
    }

    /**
     * Scope query to only include active students
     */
    public function scopeActiveStudents($query)
    {
        return $query->where('role', 'student')->where('is_active', true);
    }

    /**
     * Scope query to only include alumni
     */
    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni')->orWhere('is_active', false);
    }

    /**
     * Scope query to only include admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}
