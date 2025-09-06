@extends('layouts.main2')

@section('title', $article->title)

@section('content')
<div class="bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Judul dan Metadata -->
        <header class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-4">
                {{ $article->title }}
            </h1>
            <div class="flex items-center text-gray-500 text-sm">
                <p>Oleh <span class="font-semibold">{{ $article->user->name ?? 'Tim Redaksi' }}</span></p>
                <span class="mx-2">•</span>
                <p>{{ $article->published_at->format('d F Y, H:i') }} WIB</p>
            </div>
            @if ($article->category)
                <a href="{{ route('category.show', $article->category->slug) }}" 
                class="mt-4 inline-block bg-gemala-blue text-white px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $article->category->name }}
                </a>
            @endif
        </header>

        <!-- Gambar Utama -->
        <figure class="mb-8">
            <img src="{{ Storage::url($mainFeatured->image) }}" alt="{{ $article->title }}" class="w-full h-auto rounded-lg shadow-lg">
            @if($article->image_caption)
                <figcaption class="text-center text-sm text-gray-500 mt-2 italic">
                    {{ $article->image_caption }}
                </figcaption>
            @endif
        </figure>

        <!-- Isi Konten Berita -->
        <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
            {!! $article->content !!}
        </article>

        <hr class="my-12 border-gray-200">

        <!-- Berita Terkait -->
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Berita Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedArticles as $related)
                <a href="{{ route('news.show', $related->slug) }}" class="news-card block bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ Storage::url($mainFeatured->image) }}" alt="{{ $related->title }}" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900 line-clamp-2">{{ $related->title }}</h4>
                    </div>
                </a>
                @empty
                <p class="md:col-span-4 text-gray-500">Tidak ada berita terkait.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
