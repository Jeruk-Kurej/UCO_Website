<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasSlug;

class ProductCategory extends Model
{
    use HasFactory, HasSlug;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'business_id',
        'name',
        'slug',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
