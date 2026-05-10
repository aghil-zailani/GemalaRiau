<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'image',
        'published_at',
        'is_featured',
        'is_breaking',
        'user_id'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relasi ke Category
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    // Relasi ke Author (User)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Gambar
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id');
    // }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('content', 'like', "%{$keyword}%");
        });
    }
}
