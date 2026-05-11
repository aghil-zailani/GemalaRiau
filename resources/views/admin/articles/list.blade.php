@extends('layouts.main')

@section('title', 'Daftar Artikel')

@section('content')
<div class="bg-white p-4 sm:p-6 rounded-lg shadow-md">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Semua Artikel</h2>
        <a href="{{ route('admin.articles.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
            <i class="bi bi-plus-circle mr-1"></i> Tambah Artikel
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Gambar</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kategori</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Penulis</th>
                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status</th>
                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($articles as $index => $article)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $articles->firstItem() + $index }}</td>
                        <td class="py-3 px-4">
                            @if($article->image)
                                <img src="{{ Storage::url($article->image) }}" alt="" class="w-16 h-12 object-cover rounded">
                            @else
                                <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-medium">{{ Str::limit($article->title, 40) }}</td>
                        <td class="py-3 px-4">
                            @foreach($article->categories as $cat)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-1">{{ $cat->name }}</span>
                            @endforeach
                        </td>
                        <td class="py-3 px-4">{{ $article->user->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($article->status === 'published')
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">Published</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">Draft</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-500 hover:text-blue-700 mr-2" title="Edit">
                                <i class="bi bi-pencil-fill text-lg"></i>
                            </a>
                            <form action="{{ route('admin.articles.toggleStatus', $article->id) }}" method="POST" class="inline-block mr-2">
                                @csrf @method('PATCH')
                                @if($article->status === 'published')
                                    <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Jadikan Draft"><i class="bi bi-arrow-down-circle-fill text-lg"></i></button>
                                @else
                                    <button type="submit" class="text-green-500 hover:text-green-700" title="Publish"><i class="bi bi-arrow-up-circle-fill text-lg"></i></button>
                                @endif
                            </form>
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus"><i class="bi bi-trash-fill text-lg"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <p class="text-lg mb-2">Belum ada artikel.</p>
                            <a href="{{ route('admin.articles.create') }}" class="text-blue-600 hover:underline">Buat artikel pertama Anda →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>
@endsection
