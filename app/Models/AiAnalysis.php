<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'testimony_id',
        'sentiment_score',
        'rejection_reason',
        'is_approved',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sentiment_score' => 'decimal:2',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the testimony that owns this AI analysis
     */
    public function testimony(): BelongsTo
    {
        return $this->belongsTo(Testimony::class);
    }
}
