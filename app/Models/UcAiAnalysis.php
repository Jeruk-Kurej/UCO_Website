<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UcAiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'uc_testimony_id',
        'sentiment_score',
        'sentiment_label',
        'rejection_reason',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'sentiment_score' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function ucTestimony(): BelongsTo
    {
        return $this->belongsTo(UcTestimony::class);
    }
}
