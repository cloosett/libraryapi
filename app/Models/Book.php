<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Book extends Model
{
    use HasFactory; use HasApiTokens;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'year',
        'genre',
        'description',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
