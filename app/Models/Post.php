<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // If your table name is not plural 'posts', specify it:
    // protected $table = 'posts';

    // Mass assignable columns
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'views',
        'category_id',
        'status',
    ];
}
