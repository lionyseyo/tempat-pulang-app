<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = ['user_id', 'message', 'is_bot'];

    // Relasi balik ke User (Setiap chat milik satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}