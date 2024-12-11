<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempToken extends Model
{
    protected $table = "temp_token";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bearer_token',
        
    ];

}
