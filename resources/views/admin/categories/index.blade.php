@extends('layouts.main')

@section('title', 'Daftar Kategori')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Kategori -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kategori</h2>
            <form action="{{-- route('admin.categories.store') --}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Teknologi" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Kategori</h2>

            <!-- Session Message -->
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
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Slug</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Jumlah Artikel</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($categories as $index => $category)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4">{{ $categories->firstItem() + $index }}</td>
                                <td class="py-3 px-4 font-medium">{{ $category->name }}</td>
                                <td class="py-3 px-4">{{ $category->slug }}</td>
                                <td class="py-3 px-4 text-center">{{ $category->articles_count }}</td>
                                <td class="py-3 px-4 text-center">
                                    {{-- Tombol Edit bisa ditambahkan di sini jika perlu --}}
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua artikel dalam kategori ini juga akan terpengaruh.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="bi bi-trash-fill text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    Belum ada kategori yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <!-- Pagination -->
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
