@extends('layouts.main2')

@section('title', $article->title)

@push('styles')
<meta name="description" content="{{ Str::limit(strip_tags($article->excerpt), 160) }}">
<meta property="og:title" content="{{ $article->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($article->excerpt), 160) }}">
<meta property="og:image" content="{{ Storage::url($article->image) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
<style>
    /* Batasi ukuran gambar di dalam konten berita agar tidak terlalu besar */
    article.prose img {
        max-height: 450px !important;
        width: auto !important;
        margin-left: auto !important;
        margin-right: auto !important;
        object-fit: contain !important;
        border-radius: 0.5rem;
    }
</style>
@endpush

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
            @if ($article->categories->first())
                <a href="{{ route('category.show', $article->categories->first()->slug) }}" 
                class="mt-4 inline-block bg-gemala-blue text-white px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $article->categories->first()->name }}
                </a>
            @endif
        </header>

        <!-- Gambar Utama -->
        <figure class="mb-8 flex flex-col items-center">
            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}" class="w-full max-w-3xl h-[300px] sm:h-[400px] object-cover rounded-lg shadow-lg">
            @if($article->image_caption)
                <figcaption class="text-center text-sm text-gray-500 mt-2 italic">
                    {{ $article->image_caption }}
                </figcaption>
            @endif
        </figure>

        <!-- Tombol Share -->
        <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-200">
            <span class="text-sm font-semibold text-gray-600">Bagikan:</span>
            <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 hover:bg-green-600 text-white transition-colors" title="Bagikan ke WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition-colors" title="Bagikan ke Facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-black hover:bg-gray-800 text-white transition-colors" title="Bagikan ke X/Twitter">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>alert('Link berhasil disalin!'))" 
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 transition-colors" title="Salin Link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
            </button>
        </div>

        <div class="mb-8">
            <x-ad-placement position="article_top" />
        </div>

        <!-- Isi Konten Berita -->
        <article class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
            {!! $article->content !!}
        </article>

        <div class="mt-8">
            <x-ad-placement position="article_bottom" />
        </div>

        <hr class="my-12 border-gray-200">

        <!-- Berita Terkait -->
        <div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Berita Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedArticles as $related)
                <a href="{{ route('news.show', $related->slug) }}" class="news-card block bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}" class="w-full h-32 object-cover">
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
