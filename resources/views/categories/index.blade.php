@extends('layouts.main2')

@section('title', 'Semua Kategori Berita')

@section('content')
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Judul Halaman -->
        <div class="text-center mb-12 border-b pb-6">
            <h1 class="text-4xl font-bold text-gray-900">Semua Kategori</h1>
            <p class="text-gray-600 mt-2">Jelajahi semua topik berita yang kami liput.</p>
        </div>

        <!-- Daftar Kategori -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="block bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg transition-all transform hover:scale-105 cursor-pointer border border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                    <span class="text-xs text-gray-500 mt-2 block">{{ $category->articles_count }} artikel</span>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg">Belum ada kategori yang dibuat.</p>
            </div>
        @endif
    </div>
</div>
@endsection
