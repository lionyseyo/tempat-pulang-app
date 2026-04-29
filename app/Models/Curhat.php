<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curhat extends Model
{
    protected $fillable = [
        'user_id',
        'mood',
        'emoji',
        'content',
    ];
}