<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    /**
     * Get the user that owns the business
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all categories for this business
     */
    public function businessCategories(): HasMany
    {
        return $this->hasMany(BusinessCategory::class);
    }

    /**
     * Get all products for this business
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all services for this business
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get all photos for this business
     */
    public function photos(): HasMany
    {
        return $this->hasMany(BusinessPhoto::class);
    }

    /**
     * Get all contacts for this business
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class);
    }

    /**
     * Get all testimonies for this business
     */
    public function testimonies(): HasMany
    {
        return $this->hasMany(Testimony::class);
    }
}
