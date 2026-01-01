<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UcTestimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'content',
        'rating',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'rating' => 'integer',
    ];

    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(UcAiAnalysis::class);
    }
}
