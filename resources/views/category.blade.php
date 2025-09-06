@extends('layouts.main2')

@section('title', 'Kategori: ' . $category->name)

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Judul Halaman Kategori -->
        <div class="text-center mb-12 border-b pb-6">
            <h1 class="text-4xl font-bold text-gray-900">Kategori: {{ $category->name }}</h1>
            <p class="text-gray-600 mt-2">Menampilkan semua berita dalam kategori ini.</p>
        </div>

        <!-- Daftar Artikel -->
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $news)
                <a href="{{ route('news.show', $news->slug) }}" class="block news-card">
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full">
                        <img src="{{ Storage::url($mainFeatured->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <span>{{ $news->category->name ?? 'Berita' }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $news->published_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2 hover:text-gemala-blue line-clamp-2 flex-grow">
                                {{ $news->title }}
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mt-auto">
                                {{ $news->excerpt }}
                            </p>
                        </div>
                    </article>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg">Belum ada artikel di kategori ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
