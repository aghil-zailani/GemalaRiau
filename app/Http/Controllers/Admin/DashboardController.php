<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $articleCount = Article::count();
        $categoryCount = Category::count();
        
        $publishedCount = Article::where('status', 'published')->count();
        $draftCount = Article::where('status', 'draft')->count();

        $publishedArticles = Article::with('categories', 'user')
                                ->where('status', 'published')
                                ->latest('published_at')
                                ->take(5)
                                ->get();
        
        $draftArticles = Article::with('categories', 'user')
                                ->where('status', 'draft')
                                ->latest()
                                ->take(5)
                                ->get();

        $categories = Category::orderBy('name')->get();

        return view('admin.dashboard', compact(
            'articleCount', 
            'categoryCount', 
            'publishedCount',
            'draftCount',
            'publishedArticles',
            'draftArticles',
            'categories'
        ));
    }
}
