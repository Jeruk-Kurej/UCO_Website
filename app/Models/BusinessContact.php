<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'contact_type_id',
        'contact_value',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the business that owns this contact
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the contact type for this contact
     */
    public function contactType(): BelongsTo
    {
        return $this->belongsTo(ContactType::class);
    }
}
