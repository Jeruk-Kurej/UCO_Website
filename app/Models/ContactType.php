<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactType extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_name',
        'icon_class',
    ];

    /**
     * Get all business contacts using this contact type
     */
    public function businessContacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class);
    }
}
