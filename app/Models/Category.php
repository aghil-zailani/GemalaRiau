<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'slug'];

    /**
     * Relasi ke model Article (Satu kategori punya banyak artikel)
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }

}