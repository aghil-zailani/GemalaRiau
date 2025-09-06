<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    /**
     * Share data yang sering digunakan ke semua view
     */
    public function __construct()
    {
        $breakingNews = Article::where('is_breaking', true)
                                ->where('status', 'published')
                                ->latest('published_at')
                                ->first();

        View::share('breakingNews', $breakingNews ? $breakingNews->title : null);
    }

    /**
     * Menampilkan halaman utama (homepage)
     */
    public function home()
    {
        // 1. Ambil artikel utama yang di-set sebagai "featured"
        $mainFeatured = Article::with('categories')
                                ->where('is_featured', true)
                                ->where('status', 'published')
                                ->latest('published_at')
                                ->first();

        // 2. Ambil 2 berita terbaru untuk hero section
        $featuredNews = Article::where('status', 'published')
                                ->latest('published_at')
                                ->take(2)
                                ->get();
        
        // Buat array untuk menampung ID artikel yang sudah ditampilkan
        $excludeIds = [];
        if ($mainFeatured) {
            $excludeIds[] = $mainFeatured->id;
        }

        // 3. Ambil 3 berita sebagai "Side News" di samping berita utama
        $sideNews = Article::with('categories')
                            ->where('status', 'published')
                            ->whereNotIn('id', $excludeIds) // Jangan tampilkan lagi berita utama
                            ->latest('published_at')
                            ->take(3)
                            ->get();
        
        // Tambahkan ID side news ke daftar pengecualian
        $excludeIds = array_merge($excludeIds, $sideNews->pluck('id')->toArray());

        // 4. Ambil berita terbaru lainnya untuk section "Latest News"
        $latestNews = Article::with('categories')
                            ->where('status', 'published')
                            ->latest('published_at')
                            ->paginate(4); // Tampilkan 4 berita per halaman

        // 5. Ambil semua kategori untuk ditampilkan
        $categories = Category::withCount('articles')->get();

        return view('home', compact(
            'mainFeatured', 
            'featuredNews', 
            'sideNews',
            'latestNews',
            'categories'
        ));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $mainFeatured = Article::with('categories')
                                ->where('is_featured', true)
                                ->where('status', 'published')
                                ->latest('published_at')
                                ->first();
        
        $articles = $category->articles()
                             ->where('status', 'published')
                             ->latest('published_at')
                             ->paginate(12); // Tampilkan 12 artikel per halaman

        return view('category', compact('category', 'articles', 'mainFeatured'));
    }
    
    public function show(string $slug)
    {
        $article = Article::with(['categories', 'user'])
                            ->where('slug', $slug)
                            ->where('status', 'published')
                            ->firstOrFail();
                        
        $mainFeatured = Article::with('categories')
                                ->where('is_featured', true)
                                ->where('status', 'published')
                                ->latest('published_at')
                                ->first();

        // Ambil kategori pertama dari artikel (kalau ada)
        $categoryIds = $article->categories->pluck('id');

        // Ambil artikel terkait (pakai whereHas ke pivot)
        $relatedArticles = Article::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('show', compact('article', 'relatedArticles', 'mainFeatured'));
    }

    public function categoriesIndex()
    {
        $categories = Category::withCount('articles')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = Article::search($query)
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        return view('search', compact('posts', 'query'));
    }
}
