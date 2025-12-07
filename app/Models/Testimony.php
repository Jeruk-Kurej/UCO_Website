<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'testimony_status_id',
        'customer_name',
        'content',
        'rating',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'rating' => 'integer',
    ];

    /**
     * Get the business that owns this testimony
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the status of this testimony
     */
    public function testimonyStatus(): BelongsTo
    {
        return $this->belongsTo(TestimonyStatus::class);
    }
}
