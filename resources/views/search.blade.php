@extends('layouts.main2')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Hasil pencarian: "{{ $query }}"</h1>

    @if ($posts->count() > 0)
        <div class="space-y-6">
            @foreach ($posts as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                    <p class="text-gray-600 mt-2 line-clamp-3">{!! $post->excerpt ?? Str::limit(strip_tags($post->content), 150) !!}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->appends(['q' => $query])->links() }}
        </div>
    @else
        <p class="text-gray-500">Tidak ada artikel yang cocok dengan kata kunci ini.</p>
    @endif
</div>
@endsection
