<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasSlug;

class Service extends Model
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
        'description',
        'price_type',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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
}
