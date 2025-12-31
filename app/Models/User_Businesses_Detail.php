<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User_Businesses_Detail extends Model
{
    protected $table = 'user_businesses_details';
    
    protected $fillable = [
        'user_id',
        'business_id',
        'role_type',
        'Position_name',
        'Working_Date',
        'Company_Description',
        'Income',
        'end_date',
        'is_current',
    ];

    protected $casts = [
        'Working_Date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the user for this employment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business for this employment
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Scope for current active roles
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope for specific role type
     */
    public function scopeRole($query, string $roleType)
    {
        return $query->where('role_type', $roleType);
    }

    /**
     * Check if this is an owner role
     */
    public function isOwner(): bool
    {
        return $this->role_type === 'owner';
    }

    /**
     * Check if this is an employee role
     */
    public function isEmployee(): bool
    {
        return $this->role_type === 'employee';
    }

    /**
     * Check if role is still active
     */
    public function isActive(): bool
    {
        return $this->is_current && ($this->end_date === null || $this->end_date->isFuture());
    }
}
