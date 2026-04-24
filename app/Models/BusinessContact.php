<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessContact extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'business_id',
        'contact_type_id',
        'contact_value',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
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

    public function contactType(): BelongsTo
    {
        return $this->belongsTo(ContactType::class);
    }

    /**
     * Generate a clickable URL based on the contact type and value.
     */
    public function getLink(): ?string
    {
        $value = $this->contact_value;
        $slug = $this->contactType->slug;

        if (!$value) return null;

        switch ($slug) {
            case 'whatsapp':
                // Remove non-numeric characters for WhatsApp
                $phone = preg_replace('/[^0-9]/', '', $value);
                // Ensure it starts with a country code (default to 62 if starts with 0)
                if (strpos($phone, '0') === 0) {
                    $phone = '62' . substr($phone, 1);
                }
                return "https://wa.me/{$phone}";

            case 'instagram':
                // Remove @ if present
                $username = ltrim($value, '@');
                return "https://instagram.com/{$username}";

            case 'email':
                return "mailto:{$value}";

            case 'phone':
                return "tel:{$value}";

            case 'website':
            case 'facebook':
            case 'twitter':
            case 'linkedin':
            case 'tiktok':
            case 'youtube':
                // If it already starts with http, return as is
                if (preg_match('/^https?:\/\//', $value)) {
                    return $value;
                }
                return "https://{$value}";

            default:
                // Try to detect if it looks like a URL
                if (preg_match('/^https?:\/\//', $value)) {
                    return $value;
                }
                return null;
        }
    }
}
