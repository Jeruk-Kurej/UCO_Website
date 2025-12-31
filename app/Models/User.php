<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
