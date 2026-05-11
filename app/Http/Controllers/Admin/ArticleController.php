<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('categories', 'user')->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.articles.list', compact('articles', 'categories'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.index', compact('categories'));
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required',
            'content'      => 'required',
            'excerpt'      => 'required',
            'categories'   => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image'        => 'nullable|image|max:2048',
            'images.*'     => 'image|max:2048',
            'captions'     => 'array',
            'is_breaking'  => 'nullable|boolean',
            'is_featured'  => 'nullable|boolean',
            'status'       => 'in:draft,published',
            'published_at' => 'nullable|date',  
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $headlinePath = null;
        if ($request->hasFile('image')) {
            $headlinePath = $request->file('image')->store('articles', 'public'); // Simpan di 'storage/app/public/articles'
        }

        // Simpan artikel
        $article = Article::create([
            'title'        => $validated['title'],
            'slug'         => $slug,
            'content'      => $validated['content'],
            'excerpt'      => $validated['excerpt'],
            'status'       => $validated['status'],
            'published_at' => $validated['published_at'],
            'user_id'      => auth()->id(),
            'image'        => $headlinePath,
            'is_breaking'  => $request->input('is_breaking', 0), // Ambil nilai dari form, default 0
            'is_featured'  => $request->input('is_featured', 0), // Ambil nilai dari form, default 0
        ]);

        // Sinkronkan kategori
        $article->categories()->sync($validated['categories']);

        // Galeri gambar dihapus dari form, jadi logika ini tidak relevan lagi
        // if ($request->hasFile('images')) { ... }

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.index', compact('article', 'categories'));
    }

    /**
     * Memperbarui artikel di database.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'        => ['required', Rule::unique('articles')->ignore($article->id)],
            'content'      => 'required',
            'excerpt'      => 'required',
            'status'       => 'in:draft,published',
            'published_at' => 'nullable|date',
            'categories'   => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image'        => 'nullable|image|max:5120',
            'is_breaking'  => 'nullable|boolean',
            'is_featured'  => 'nullable|boolean',
        ]);
        
        // Logika gambar
        $headlinePath = $article->image;
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $headlinePath = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']), // Tetap berisiko, lebih baik pakai logika di atas
            'content'      => $validated['content'],
            'excerpt'      => $validated['excerpt'],
            'status'       => $validated['status'],
            'published_at' => $validated['published_at'],
            'image'        => $headlinePath,
            'is_breaking'  => $request->input('is_breaking', 0),
            'is_featured'  => $request->input('is_featured', 0),
        ]);

        $article->categories()->sync($validated['categories']);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(Article $article)
    {
        // Hapus gambar dari storage
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function toggleStatus(Article $article)
    {
        if ($article->status == 'published') {
            $article->update(['status' => 'draft', 'published_at' => null]);
            return back()->with('success', 'Status artikel berhasil diubah menjadi Draft.');
        } else {
            $article->update(['status' => 'published', 'published_at' => now()]);
            return back()->with('success', 'Artikel berhasil di-publish.');
        }
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ], [
            'upload.max' => 'Ukuran gambar terlalu besar. Maksimal ukuran file adalah 5MB.',
            'upload.mimes' => 'Format file tidak didukung. Harap unggah gambar dengan format: jpeg, png, gif, atau webp.',
            'upload.required' => 'Tidak ada file gambar yang diunggah.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => $validator->errors()->first('upload')
                ]
            ]);
        }

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $path = $file->store('uploads', 'public');
            $url = Storage::url($path);

            return response()->json([
                'uploaded' => 1,
                'fileName' => basename($path),
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'Terjadi kesalahan sistem saat menyimpan gambar.']
        ]);
    }
}
