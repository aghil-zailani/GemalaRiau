<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public function update(Request $request, Article $article)
    {
        // 1. Validasi semua data yang dikirim dari form modal
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:articles,title,' . $article->id,
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            // Hapus 'required' dari status jika tidak ada di form modal
            // 'status' => 'required|in:draft,published', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_caption' => 'nullable|string|max:255',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($request->title);
        // Anda bisa menambahkan field lain dari form modal di sini jika ada
        // Contoh: $data['is_featured'] = $request->has('is_featured');

        // 2. Proses update gambar jika ada gambar baru yang di-upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($article->image && File::exists(public_path($article->image))) {
                File::delete(public_path($article->image));
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['image'] = '/images/' . $filename;
        }

        // 3. Simpan semua perubahan ke database
        $article->update($data);

        // 4. Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Artikel berhasil diperbarui!');
    }
}
