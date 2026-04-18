<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasSlug;

class ContactType extends Model
{
    use HasFactory, HasSlug;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'platform_name',
        'slug',
        'icon_class',
    ];

    /**
     * Get the column name to use as the slug source.
     *
     * @return string
     */
    protected function getSlugSourceColumn()
    {
        return 'platform_name';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function businessContacts(): HasMany
    {
        return $this->hasMany(BusinessContact::class);
    }
}
