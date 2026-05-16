@extends('layouts.main2')

@section('title', 'Gemala Riau News - Berita Terkini Riau')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Tetap <span class="gradient-text">Terinformasi</span>,<br>
                    Selalu Terdepan
                </h1>
                <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                    Dapatkan berita terbaru, liputan terkini, dan analisis mendalam dari seluruh dunia. Sumber tepercaya Anda untuk jurnalisme andal.
                </p>
            </div>
            <div class="animate-slide-up">
                <div class="relative">
                    <div class="bg-gradient-to-br from-gemala-gold to-gemala-blue rounded-2xl p-8 shadow-2xl">
                        @forelse($featuredNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="block bg-white rounded-lg p-6 {{ !$loop->last ? 'mb-4' : '' }} hover:shadow-lg transition-shadow">
                            <div class="flex items-center mb-3">
                                <div class="w-3 h-3 {{ $loop->first ? 'bg-news-red' : 'bg-green-500' }} rounded-full mr-2"></div>
                                <span class="text-gray-600 text-sm">{{ $news->categories->first()->name ?? 'Berita' }}</span>
                            </div>
                            <h3 class="text-gray-900 font-bold text-lg mb-2">{{ $news->title }}</h3>
                            <p class="text-gray-600 text-sm">{{ Str::limit($news->excerpt, 50) }}</p>
                        </a>
                        @empty
                        <p class="text-white text-center">Belum ada berita untuk ditampilkan.</p>
                        @endforelse
                    </div>
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20 animate-pulse-slow"></div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($globalAds['top_header']) && $globalAds['top_header']->count() > 0)
        @php $adTop = $globalAds['top_header']->random(); @endphp
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-2 text-center">
            <span class="text-xs text-gray-400 block mb-1">
                Advertisement
                @if($adTop->advertiser_name)
                    &bull; Sponsored by {{ $adTop->advertiser_name }}
                @endif
            </span>
            <div class="relative group inline-block w-full">
                <a href="{{ $adTop->link_url }}" target="_blank" class="block">
                    <img src="{{ asset('storage/' . $adTop->image_path) }}" alt="{{ $adTop->title }}" class="mx-auto rounded-lg shadow-sm w-full max-h-32 object-cover">
                </a>
                @if($adTop->commission_amount > 0)
                    <div class="absolute bottom-2 right-4 bg-black/70 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity flex items-center pointer-events-none">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Mendukung Gemala Riau (Ad Placement)
                    </div>
                @endif
            </div>
        </div>
    @endif
</section>

<!-- Featured News Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Berita Utama</h2>
            <p class="text-gray-600 text-lg">Berita paling penting yang terjadi saat ini</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Main Featured Article -->
            <div class="md:col-span-2">
                @if($mainFeatured)
                <article class="news-card bg-white rounded-2xl shadow-lg overflow-hidden h-full flex flex-col">
                    <div class="relative">
                        <a href="{{ route('news.show', $mainFeatured->slug) }}">
                            <img src="{{ Storage::url($mainFeatured->image) }}" alt="{{ $mainFeatured->title }}" class="w-full h-80 object-cover">
                        </a>
                        <div class="absolute top-4 left-4">
                            {{-- Periksa apakah ada kategori sebelum menampilkannya --}}
                            @if ($mainFeatured->categories->first())
                                <a href="{{ route('category.show', $mainFeatured->categories->first()->slug) }}" class="bg-news-red text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $mainFeatured->categories->first()->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <span>{{ $mainFeatured->user->name ?? 'Penulis' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $mainFeatured->published_at->diffForHumans() }}</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3 hover:text-gemala-blue">
                            <a href="{{ route('news.show', $mainFeatured->slug) }}">
                                {{ $mainFeatured->title }}
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4 leading-relaxed flex-grow">
                            {{ $mainFeatured->excerpt }}
                        </p>
                        <a href="{{ route('news.show', $mainFeatured->slug) }}" class="inline-flex items-center text-gemala-blue font-semibold hover:text-yellow-600 transition-colors mt-auto">
                            Baca Selengkapnya 
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </article>
                @else
                <div class="h-full flex items-center justify-center bg-gray-100 rounded-2xl">
                    <p class="text-gray-500">Tidak ada berita utama saat ini.</p>
                </div>
                @endif
            </div>

            <!-- Side Articles -->
            <div class="space-y-6">
                @if(isset($globalAds['sidebar']) && $globalAds['sidebar']->count() > 0)
                    @php $adSidebar = $globalAds['sidebar']->random(); @endphp
                    <div class="bg-gray-50 rounded-xl p-2 text-center shadow-sm relative group">
                        <span class="text-[10px] text-gray-400 block mb-1 uppercase">
                            Advertisement
                            @if($adSidebar->advertiser_name)
                                <br>Sponsored by {{ $adSidebar->advertiser_name }}
                            @endif
                        </span>
                        <div class="relative">
                            <a href="{{ $adSidebar->link_url }}" target="_blank" class="block">
                                <img src="{{ asset('storage/' . $adSidebar->image_path) }}" alt="{{ $adSidebar->title }}" class="w-full rounded object-cover">
                            </a>
                            @if($adSidebar->commission_amount > 0)
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity flex items-center pointer-events-none">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Ad Placement
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                @forelse($sideNews as $news)
                <a href="{{ route('news.show', $news->slug) }}" class="news-card bg-white rounded-xl shadow-lg overflow-hidden flex hover:shadow-xl transition-shadow">
                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-1/3 h-full object-cover">
                    <div class="p-4 w-2/3">
                        <span class="text-xs text-gemala-blue font-bold uppercase">{{ $news->categories->first()->name ?? 'Berita' }}</span>
                        <h3 class="font-bold text-gray-900 mt-1 hover:text-gemala-blue line-clamp-3">
                            {{ $news->title }}
                        </h3>
                    </div>
                </a>
                @empty
                <p class="text-gray-500">Tidak ada berita lainnya.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Berita Terbaru</h2>
            <p class="text-gray-600 text-lg">Ikuti terus berita-berita terbaru</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latestNews as $news)
            <a href="{{ route('news.show', $news->slug) }}" class="block news-card">
                <article class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full">
                    <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="flex items-center text-xs text-gray-500 mb-2">
                            <span>{{ $news->categories->first()->name ?? 'Berita' }}</span>
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
            @empty
            <p class="md:col-span-4 text-center text-gray-500">Tidak ada berita terbaru.</p>
            @endforelse
        </div>
        
        <div class="mt-12">
            {{ $latestNews->links() }}
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Jelajahi Kategori</h2>
            <p class="text-gray-600 text-lg">Temukan berita yang penting bagi Anda</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-6">
            @forelse($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="block bg-gray-50 rounded-xl px-8 py-4 text-center hover:shadow-lg transition-all transform hover:scale-105 cursor-pointer border border-gray-100">
                <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                <span class="text-xs text-gray-500 mt-1 block">{{ $category->articles_count }} artikel</span>
            </a>
            @empty
            <p class="w-full text-center text-gray-500">Belum ada kategori.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
