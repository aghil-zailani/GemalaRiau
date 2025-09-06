@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Banner -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang di Panel Admin</h2>
        <p class="text-gray-700">
            Dari sini Anda dapat mengelola semua konten untuk situs berita Gemala Riau News.
        </p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Artikel Card -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-blue-500 text-white rounded-full p-4 mr-4">
                <i class="bi bi-newspaper text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500">Total Artikel</p>
                <p class="text-3xl font-bold">{{ $articleCount }}</p>
            </div>
        </div>
        <!-- Total Kategori Card -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-gray-500 text-white rounded-full p-4 mr-4">
                <i class="bi bi-tags text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500">Total Kategori</p>
                <p class="text-3xl font-bold">{{ $categoryCount }}</p>
            </div>
        </div>
        <!-- Artikel Dipublish Card -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-green-500 text-white rounded-full p-4 mr-4">
                <i class="bi bi-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500">Artikel Dipublish</p>
                <p class="text-3xl font-bold">{{ $publishedCount }}</p>
            </div>
        </div>
        <!-- Artikel Draft Card -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-yellow-500 text-white rounded-full p-4 mr-4">
                <i class="bi bi-pencil-square text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500">Artikel Draft</p>
                <p class="text-3xl font-bold">{{ $draftCount }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel Artikel Terakhir Dipublish -->
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Artikel Terakhir Dipublish</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Penulis</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal Publish</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($publishedArticles as $article)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-4 font-medium">{{ Str::limit($article->title, 45) }}</td>
                            <td class="py-3 px-4">{{ $article->user->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $article->published_at ? $article->published_at : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.articles.toggleStatus', $article->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded-full" title="Ubah ke Draft">
                                        Jadikan Draft
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Belum ada artikel yang di-publish.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Artikel Draft -->
    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Artikel Draft</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Penulis</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Terakhir Diubah</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($draftArticles as $article)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-4 font-medium">{{ Str::limit($article->title, 45) }}</td>
                            <td class="py-3 px-4">{{ $article->author->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $article->updated_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <!-- TOMBOL EDIT YANG SUDAH DIPERBAIKI -->
                                <button type="button" 
                                        class="edit-article-btn bg-gray-500 hover:bg-gray-600 text-white text-xs font-bold py-1 px-3 rounded-full mr-2" 
                                        title="Edit Artikel"
                                        data-action="{{ route('admin.articles.update', $article->id) }}"
                                        data-article="{{ $article->toJson() }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.articles.toggleStatus', $article->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-3 rounded-full" title="Publish Artikel">
                                        Publish
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Tidak ada artikel dalam draft.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT YANG SUDAH DIPERBAIKI -->
    <div id="editArticleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-full overflow-y-auto">
            <form id="editArticleForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-xl font-bold">Edit Artikel Draft</h3>
                        <button type="button" id="closeModalBtn" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="lg:col-span-2 space-y-4">
                            <div>
                                <label for="modal_title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                                <input type="text" id="modal_title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="modal_excerpt" class="block text-sm font-medium text-gray-700">Ringkasan (Excerpt)</label>
                                <textarea id="modal_excerpt" name="excerpt" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                            </div>
                            <div>
                                <label for="modal_content" class="block text-sm font-medium text-gray-700">Isi Konten</label>
                                <textarea id="modal_content" name="content" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div class="lg:col-span-1 space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label for="modal_category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select id="modal_category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label for="modal_image" class="block text-sm font-medium text-gray-700">Ganti Gambar Utama</label>
                                <input type="file" name="image" id="modal_image" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <label for="modal_image_caption" class="block text-sm font-medium text-gray-700 mt-4">Keterangan Gambar</label>
                                <input type="text" id="modal_image_caption" name="image_caption" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 px-6 py-3 flex justify-end">
                    <button type="button" id="cancelModalBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editArticleModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const editForm = document.getElementById('editArticleForm');
    
    // Ambil semua elemen form di dalam modal
    const modalTitle = document.getElementById('modal_title');
    const modalExcerpt = document.getElementById('modal_excerpt');
    const modalContent = document.getElementById('modal_content');
    const modalCategoryId = document.getElementById('modal_category_id');
    const modalImageCaption = document.getElementById('modal_image_caption');

    // Buka modal saat tombol edit diklik
    document.querySelectorAll('.edit-article-btn').forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari data attributes tombol
            const action = this.dataset.action;
            const article = JSON.parse(this.dataset.article);

            // Isi form di dalam modal dengan data artikel
            editForm.action = action;
            modalTitle.value = article.title;
            modalExcerpt.value = article.excerpt;
            modalContent.value = article.content;
            modalCategoryId.value = article.category_id;
            modalImageCaption.value = article.image_caption;
            
            // Tampilkan modal
            modal.classList.remove('hidden');
        });
    });

    // Fungsi untuk menutup modal
    function closeModal() {
        modal.classList.add('hidden');
    }

    // Event listener untuk tombol-tombol penutup modal
    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);

    // Tutup modal jika klik di luar area modal
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});
</script>
@endpush
