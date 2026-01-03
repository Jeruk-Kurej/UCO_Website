<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'profile_photo_url',
        'role',
        'is_active',
        'birth_date',
        'birth_city',
        'religion',
        'phone_number',
        'mobile_number',
        'whatsapp',
        'NIS',
        'Student_Year',
        'Major',
        'Is_Graduate',
        'CGPA',
        'personal_data',
        'academic_data',
        'father_data',
        'mother_data',
        'graduation_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'Is_Graduate' => 'boolean',
            'birth_date' => 'date',
            'CGPA' => 'decimal:2',
            'personal_data' => 'array',
            'academic_data' => 'array',
            'father_data' => 'array',
            'mother_data' => 'array',
            'graduation_data' => 'array',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function businessAssignments(): HasMany
    {
        return $this->hasMany(User_Businesses_Detail::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student' && $this->is_active;
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni' || !$this->is_active;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActiveStudents($query)
    {
        return $query->where('role', 'student')->where('is_active', true);
    }

    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni')->orWhere('is_active', false);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}
