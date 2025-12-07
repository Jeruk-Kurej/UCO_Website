<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'photo_url',
        'caption',
    ];

    /**
     * Get the business that owns this photo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
