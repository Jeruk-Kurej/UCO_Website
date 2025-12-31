<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Businesses_Detail extends Model
{
    protected $fillable = [
        'Position_name',
        'Working_Date',
        'Company_Description',
        'Income',
        
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
