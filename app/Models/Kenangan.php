<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kenangan extends Model
{
    protected $fillable = [
        'user_id',
        'caption',
        'foto',
        'video'
    ];
}