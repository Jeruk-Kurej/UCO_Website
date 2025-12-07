<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_name',
        'content',
        'rating',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'rating' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the AI analysis for this testimony (One-to-One)
     */
    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class);
    }
}
