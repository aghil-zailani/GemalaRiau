@extends('layouts.main')

@section('title', isset($article) ? 'Edit Artikel' : 'Tambah Artikel Baru')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ isset($article) ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h2>

    <form action="{{ isset($article) ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($article))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Konten Utama -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Judul -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                    <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-lg shadow-sm" value="{{ old('title', $article->title ?? '') }}" required>
                </div>
                <!-- Excerpt / Ringkasan -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                </div>
                <!-- Konten -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten</label>
                    <textarea name="content" id="editor" rows="12" class="w-full border-gray-300 rounded-lg shadow-sm">
                        {{ old('content', $article->content ?? '') }}
                    </textarea>
                </div>
            </div>

            <!-- Kolom Kanan: Metadata & Opsi -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Tombol Aksi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                     <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        {{ isset($article) ? 'Perbarui Artikel' : 'Simpan Artikel' }}
                    </button>
                </div>
                <!-- Status & Tanggal Terbit -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="draft" @selected(old('status', $article->status ?? '') == 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $article->status ?? '') == 'published')>Published</option>
                    </select>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mt-4 mb-1">Tanggal Terbit (Opsional)</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', isset($article->published_at) ? $article->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <!-- Kategori -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    @foreach($categories as $category)
                        <div class="flex items-center mb-1">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}" 
                                @if(isset($article) && $article->categories->contains($category->id)) checked @endif 
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-900">{{ $category->name }}</label>
                        </div>
                    @endforeach 
                </div>
                <!-- Opsi Tambahan -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                    <input type="hidden" name="is_featured" value="0">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $article->is_featured ?? false)) class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Jadikan Berita Utama (Featured)</label>
                    </div>
                    
                    <input type="hidden" name="is_breaking" value="0">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_breaking" id="is_breaking" value="1" @checked(old('is_breaking', $article->is_breaking ?? false)) class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="is_breaking" class="ml-2 block text-sm text-gray-900">Jadikan Berita Terkini (Breaking)</label>
                    </div>
                </div>
                <!-- Galeri Gambar -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Utama</label>
                    <input type="file" name="image" id="image"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <small class="text-gray-500">Pilih satu gambar utama untuk artikel</small>
                    
                    @if(isset($article) && $article->image)
                        <div class="mt-4">
                            <p class="text-xs text-gray-500">Gambar saat ini:</p>
                            <img src="{{ Storage::url($article->image) }}" alt="Gambar saat ini" class="mt-2 w-full h-auto rounded-lg shadow-sm">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: "{{ route('admin.ckeditor.upload').'?_token='.csrf_token() }}"
        }
    })
    .catch(error => {
        console.error(error);
    });
</script>

@endsection
