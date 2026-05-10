@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang di Panel Admin</h2>
        <p class="text-gray-700">Dari sini Anda dapat mengelola semua konten untuk situs berita Gemala Riau News.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-blue-500 text-white rounded-full p-4 mr-4"><i class="bi bi-newspaper text-2xl"></i></div>
            <div><p class="text-gray-500">Total Artikel</p><p class="text-3xl font-bold">{{ $articleCount }}</p></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-gray-500 text-white rounded-full p-4 mr-4"><i class="bi bi-tags text-2xl"></i></div>
            <div><p class="text-gray-500">Total Kategori</p><p class="text-3xl font-bold">{{ $categoryCount }}</p></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-green-500 text-white rounded-full p-4 mr-4"><i class="bi bi-check-circle text-2xl"></i></div>
            <div><p class="text-gray-500">Artikel Dipublish</p><p class="text-3xl font-bold">{{ $publishedCount }}</p></div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-yellow-500 text-white rounded-full p-4 mr-4"><i class="bi bi-pencil-square text-2xl"></i></div>
            <div><p class="text-gray-500">Artikel Draft</p><p class="text-3xl font-bold">{{ $draftCount }}</p></div>
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
                            <td class="py-3 px-4">{{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.articles.toggleStatus', $article->id) }}" method="POST" class="inline-block">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded-full">Jadikan Draft</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Belum ada artikel yang di-publish.</td></tr>
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
                            <td class="py-3 px-4">{{ $article->user->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $article->updated_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white text-xs font-bold py-1 px-3 rounded-full mr-2">Edit</a>
                                <form action="{{ route('admin.articles.toggleStatus', $article->id) }}" method="POST" class="inline-block">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-3 rounded-full">Publish</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada artikel dalam draft.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
