<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageMapping extends Model
{
    protected $fillable = [
        'url_hash',
        'source_url',
        'stored_path',
        'disk',
    ];
}
