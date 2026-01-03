<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'business_type_id',
        'business_mode',
        'name',
        'description',
        'position',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isProductMode(): bool
    {
        return in_array($this->business_mode, ['product', 'both']);
    }

    public function isServiceMode(): bool
    {
        return in_array($this->business_mode, ['service', 'both']);
    }

    public function isBothMode(): bool
    {
        return $this->business_mode === 'both';
    }

    public function isCollegeProject(): bool
    {
        return $this->is_from_college_project === true;
    }

    public function hasLegalDocuments(): bool
    {
        return !empty($this->legal_documents);
    }

    public function hasCertifications(): bool
    {
        return !empty($this->product_certifications);
    }

    public function isActive(): bool
    {
        return $this->is_continued_after_graduation === true;
    }

    public function getAgeInYears(): ?int
    {
        if (!$this->established_date) {
            return null;
        }
        
        return $this->established_date->diffInYears(now());
    }
}

