<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_type_id', // CHANGED from business_id
        'name',
    ];

    /**
     * Get the business type that owns this category
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    /**
     * Get all products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
