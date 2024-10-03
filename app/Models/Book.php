<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

     protected $fillable = [
        'title', 
        'author', 
        'file',
        'publish', 
        'cover_image', 
        'size', 
        'number_pages', 
        'published_at', 
        'is_downloadable', 
        'category_id',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
