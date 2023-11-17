<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'images',
        'is_active'
    ];

    protected $casts = [
        'images'    => 'array',
        'is_active' => 'boolean',
    ];
}
