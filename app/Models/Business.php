<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_type_id',
        'business_mode',
        'name',
        'description',
        'position', // User's position in this business
        
        // Enhanced Fields
        'logo_url',
        'established_date',
        'address',
        'employee_count',
        'revenue_range',
        'is_from_college_project',
        'is_continued_after_graduation',
        'legal_documents',
        'product_certifications',
        'business_challenges',
    ];

    protected $casts = [
        'established_date' => 'date',
        'is_from_college_project' => 'boolean',
        'is_continued_after_graduation' => 'boolean',
        'legal_documents' => 'array',
        'product_certifications' => 'array',
        'business_challenges' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(BusinessPhoto::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class);
    }

    /**
     * Check if business is in product mode
     */
    public function isProductMode(): bool
    {
        return in_array($this->business_mode, ['product', 'both']);
    }

    /**
     * Check if business is in service mode
     */
    public function isServiceMode(): bool
    {
        return in_array($this->business_mode, ['service', 'both']);
    }

    /**
     * Check if business has both products and services
     */
    public function isBothMode(): bool
    {
        return $this->business_mode === 'both';
    }

    /**
     * Get all team members (many-to-many with pivot data)
     * Uses user_businesses_details pivot table
     */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_businesses_details')
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
     * Get current active team members only
     */
    public function currentTeam(): BelongsToMany
    {
        return $this->teamMembers()->wherePivot('is_current', true);
    }

    /**
     * Get founders/owners
     */
    public function founders(): BelongsToMany
    {
        return $this->teamMembers()->wherePivot('role_type', 'owner');
    }

    /**
     * Get employees
     */
    public function employees(): BelongsToMany
    {
        return $this->teamMembers()
                    ->wherePivot('role_type', 'employee')
                    ->wherePivot('is_current', true);
    }

    /**
     * Get business employment details (direct access to pivot)
     */
    public function employmentDetails(): HasMany
    {
        return $this->hasMany(User_Businesses_Detail::class);
    }

    /**
     * Check if business is from college project
     */
    public function isCollegeProject(): bool
    {
        return $this->is_from_college_project === true;
    }

    /**
     * Get total team size
     */
    public function teamSize(): int
    {
        return $this->currentTeam()->count();
    }

    /**
     * Check if user is part of this business
     */
    public function hasTeamMember(User $user): bool
    {
        return $this->teamMembers()->where('user_id', $user->id)->exists();
    }

    /**
     * Get business age in years
     */
    public function getAgeInYears(): ?int
    {
        if (!$this->established_date) {
            return null;
        }
        
        return $this->established_date->diffInYears(now());
    }

    /**
     * Check if business has legal documents
     */
    public function hasLegalDocuments(): bool
    {
        return !empty($this->legal_documents);
    }

    /**
     * Check if products have certifications
     */
    public function hasCertifications(): bool
    {
        return !empty($this->product_certifications);
    }

    /**
     * Get formatted revenue range label
     */
    public function getRevenueLabel(): string
    {
        if (!$this->revenue_range) {
            return 'Not specified';
        }
        
        return $this->revenue_range;
    }

    /**
     * Check if business is still operating
     */
    public function isActive(): bool
    {
        return $this->is_continued_after_graduation === true;
    }

    /**
     * Get total challenges count
     */
    public function getChallengesCount(): int
    {
        return !empty($this->business_challenges) ? count($this->business_challenges) : 0;
    }
}
