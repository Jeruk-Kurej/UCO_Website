<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestimonyStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get all testimonies with this status
     */
    public function testimonies(): HasMany
    {
        return $this->hasMany(Testimony::class);
    }
}
