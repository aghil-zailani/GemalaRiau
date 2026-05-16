@extends('layouts.main')

@section('title', isset($article) ? 'Edit Artikel' : 'Tambah Artikel Baru')

@push('styles')
<style>
    /* CKEditor Custom Styling */
    .ck-editor__editable {
        min-height: 450px !important;
        font-size: 16px !important;
        line-height: 1.8 !important;
        font-family: 'Georgia', 'Times New Roman', serif !important;
    }
    .ck-editor__editable:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }
    .ck-editor__editable blockquote {
        border-left: 4px solid #1e3a8a !important;
        padding: 12px 20px !important;
        margin: 20px 0 !important;
        background: #f0f4ff !important;
        font-style: italic !important;
        color: #374151 !important;
        border-radius: 0 8px 8px 0 !important;
    }
    .ck-editor__editable figure.image {
        margin: 24px 0 !important;
        text-align: center;
    }
    .ck-editor__editable figure.image figcaption {
        font-size: 13px !important;
        color: #6b7280 !important;
        font-style: italic !important;
        padding: 8px 12px !important;
        background: #f9fafb !important;
        border-radius: 0 0 8px 8px !important;
    }
    .ck-editor__editable figure.image img {
        border-radius: 8px 8px 0 0 !important;
        max-width: 100% !important;
        max-height: 400px !important;
        object-fit: contain !important;
    }
    .ck.ck-editor {
        border-radius: 12px !important;
        overflow: hidden !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
    }
    .ck.ck-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 6px 8px !important;
    }

    /* Image Preview */
    .image-preview-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .image-preview-container:hover .image-overlay {
        opacity: 1;
    }
    .image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 12px;
    }

    /* Card styling */
    .sidebar-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        transition: box-shadow 0.2s ease;
    }
    .sidebar-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .sidebar-card-title {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sidebar-card-title i {
        color: #6b7280;
    }

    /* Toggle Switch */
    .toggle-switch { position: relative; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; cursor: pointer; inset: 0;
        background: #d1d5db; border-radius: 24px; transition: 0.3s;
    }
    .toggle-slider:before {
        content: ""; position: absolute; height: 18px; width: 18px;
        left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-switch input:checked + .toggle-slider { background: #3b82f6; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }
</style>
@endpush

@section('content')

{{-- Error Messages --}}
@if ($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6" role="alert">
    <div class="flex items-center mb-2">
        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
        <strong>Terdapat kesalahan pada input Anda:</strong>
    </div>
    <ul class="list-disc list-inside text-sm space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ isset($article) ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
    @csrf
    @if(isset($article)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- ========== KOLOM KIRI: KONTEN UTAMA ========== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Judul Artikel --}}
            <div>
                <input type="text" name="title" id="title" 
                    value="{{ old('title', $article->title ?? '') }}" required
                    placeholder="Tulis judul artikel di sini..."
                    class="w-full text-3xl font-bold border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-3 placeholder-gray-300 transition-colors bg-transparent">
            </div>

            {{-- Ringkasan --}}
            <div>
                <label for="excerpt" class="block text-sm font-semibold text-gray-600 mb-2">
                    <i class="bi bi-card-text mr-1"></i> Ringkasan Artikel
                </label>
                <textarea name="excerpt" id="excerpt" rows="3" required
                    placeholder="Tulis ringkasan singkat yang akan tampil di halaman utama..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all resize-none text-gray-700">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Ringkasan ini akan ditampilkan di halaman utama dan hasil pencarian.</p>
            </div>

            {{-- Editor Konten --}}
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    <i class="bi bi-body-text mr-1"></i> Isi Konten Artikel
                </label>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <textarea name="content" id="editor">{{ old('content', $article->content ?? '') }}</textarea>
                </div>
                <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                    <span><i class="bi bi-image mr-1"></i> Sisipkan gambar dengan tombol di toolbar</span>
                    <span><i class="bi bi-quote mr-1"></i> Gunakan tombol kutipan untuk blockquote</span>
                </div>
            </div>
        </div>

        {{-- ========== KOLOM KANAN: PANEL PENGATURAN ========== --}}
        <div class="lg:col-span-1 space-y-5">            

            {{-- Status & Jadwal --}}
            <div class="sidebar-card">
                <div class="sidebar-card-title"><i class="bi bi-gear"></i> Pengaturan Publikasi</div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm text-gray-600 mb-1.5">Status</label>
                    <select name="status" id="status" class="w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="draft" @selected(old('status', $article->status ?? '') == 'draft')>📝 Draft</option>
                        <option value="published" @selected(old('status', $article->status ?? '') == 'published')>🌐 Published</option>
                    </select>
                </div>

                <div>
                    <label for="published_at" class="block text-sm text-gray-600 mb-1.5">Jadwal Terbit</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                        value="{{ old('published_at', isset($article->published_at) ? $article->published_at->format('Y-m-d\TH:i') : '') }}" 
                        class="w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan untuk terbit langsung saat publish.</p>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="sidebar-card">
                <div class="sidebar-card-title"><i class="bi bi-bookmark"></i> Kategori</div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($categories as $category)
                    <label for="category-{{ $category->id }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}" 
                            @if(isset($article) && $article->categories->contains($category->id)) checked @endif 
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                @if(count($categories) === 0)
                    <p class="text-sm text-gray-400 text-center py-2">Belum ada kategori.</p>
                @endif
            </div>

            {{-- Opsi Tambahan --}}
            <div class="sidebar-card">
                <div class="sidebar-card-title"><i class="bi bi-stars"></i> Sorotan</div>
                
                <input type="hidden" name="is_featured" value="0">
                <div class="flex items-center justify-between py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Berita Utama</p>
                        <p class="text-xs text-gray-400">Tampil di section featured</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $article->is_featured ?? false))>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="border-t border-gray-100 mt-2 pt-2"></div>

                <input type="hidden" name="is_breaking" value="0">
                <div class="flex items-center justify-between py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Berita Terkini</p>
                        <p class="text-xs text-gray-400">Tampil di banner breaking</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_breaking" value="1" @checked(old('is_breaking', $article->is_breaking ?? false))>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            {{-- Gambar Utama --}}
            <div class="sidebar-card">
                <div class="sidebar-card-title"><i class="bi bi-image"></i> Gambar Utama</div>
                
                {{-- Preview gambar --}}
                <div id="imagePreview" class="{{ isset($article) && $article->image ? '' : 'hidden' }}">
                    <div class="image-preview-container mb-3">
                        <img id="previewImg" src="{{ isset($article) && $article->image ? Storage::url($article->image) : '' }}" 
                             alt="Preview" class="w-full h-44 object-cover rounded-xl">
                        <div class="image-overlay">
                            <span class="text-white text-sm font-medium"><i class="bi bi-pencil mr-1"></i> Ganti Gambar</span>
                        </div>
                    </div>
                </div>

                <label for="image" class="block cursor-pointer">
                    <div id="uploadArea" class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all {{ isset($article) && $article->image ? 'hidden' : '' }}">
                        <i class="bi bi-cloud-arrow-up text-3xl text-gray-300 mb-2 block"></i>
                        <p class="text-sm text-gray-500">Klik untuk upload gambar</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP (Maks. 2MB)</p>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*" class="hidden">
                </label>
                <button type="button" id="removeImageBtn" class="text-xs text-red-500 hover:text-red-700 mt-2 {{ isset($article) && $article->image ? '' : 'hidden' }}">
                    <i class="bi bi-x-circle mr-1"></i> Hapus preview
                </button>
            </div>

            {{-- Tombol Aksi --}}
            <div class="sidebar-card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <i class="bi bi-{{ isset($article) ? 'arrow-repeat' : 'cloud-arrow-up' }}"></i>
                    {{ isset($article) ? 'Perbarui Artikel' : 'Publish Artikel' }}
                </button>
                @if(isset($article))
                <a href="{{ route('admin.articles.index') }}" class="w-full mt-2 inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-600 font-medium py-2.5 px-4 rounded-xl border border-gray-200 transition-all gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
                @endif
            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Image Preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
            document.getElementById('removeImageBtn').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('removeImageBtn').addEventListener('click', function() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
    this.classList.add('hidden');
});

// CKEditor 5 Configuration
ClassicEditor
    .create(document.querySelector('#editor'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', '|',
                'uploadImage', '|',
                'insertTable', '|',
                'undo', 'redo'
            ],
            shouldNotGroupWhenFull: true
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Judul Besar', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Sub Judul', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Judul Kecil', class: 'ck-heading_heading4' }
            ]
        },
        image: {
            toolbar: [
                'imageTextAlternative',
                'toggleImageCaption',
                '|',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side'
            ]
        },
        ckfinder: {
            uploadUrl: "{{ route('admin.ckeditor.upload').'?_token='.csrf_token() }}"
        },
        placeholder: 'Mulai menulis isi artikel Anda di sini...',
        language: 'id'
    })
    .then(editor => {
        window.editorInstance = editor;
    })
    .catch(error => {
        console.error('CKEditor Error:', error);
    });
</script>
@endpush
