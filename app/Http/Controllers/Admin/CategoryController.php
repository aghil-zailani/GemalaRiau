<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dan form tambah.
     */
    public function index()
    {
        // withCount akan menghitung jumlah artikel di setiap kategori
        $categories = Category::withCount('articles')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Buat kategori baru
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Cek jika kategori masih memiliki artikel
        if ($category->articles()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
