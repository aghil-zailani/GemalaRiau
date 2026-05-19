@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Iklan: {{ $advertisement->title }}</h2>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom Kiri -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Judul Iklan (Internal)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" type="text" required value="{{ old('title', $advertisement->title) }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="advertiser_name">Nama Pengiklan / Klien</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="advertiser_name" name="advertiser_name" type="text" value="{{ old('advertiser_name', $advertisement->advertiser_name) }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="link_url">URL Tujuan (Opsional)</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link_url" name="link_url" type="url" value="{{ old('link_url', $advertisement->link_url) }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="position">Posisi Iklan</label>
                            <div class="relative">
                                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="position" name="position" required>
                                    <option value="top_header" {{ $advertisement->position == 'top_header' ? 'selected' : '' }}>Header Semua Halaman (Top Header)</option>
                                    <option value="home_middle" {{ $advertisement->position == 'home_middle' ? 'selected' : '' }}>Tengah Beranda (Home Middle)</option>
                                    <option value="article_top" {{ $advertisement->position == 'article_top' ? 'selected' : '' }}>Atas Artikel (Article Top)</option>
                                    <option value="article_middle" {{ $advertisement->position == 'article_middle' ? 'selected' : '' }}>Tengah Artikel (Article Middle)</option>
                                    <option value="article_bottom" {{ $advertisement->position == 'article_bottom' ? 'selected' : '' }}>Bawah Artikel (Article Bottom)</option>
                                    <option value="sidebar" {{ $advertisement->position == 'sidebar' ? 'selected' : '' }}>Sidebar Samping</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Gambar Banner Baru (Opsional)</label>
                            <input class="w-full py-2 px-3 text-gray-700 bg-white border border-gray-300 rounded focus:outline-none focus:border-blue-500" id="image" name="image" type="file" accept="image/*,video/mp4,video/webm,video/ogg">
                            <p class="text-xs text-gray-500 mt-1 mb-2">Biarkan kosong jika tidak ingin mengubah media. Format: JPG, PNG, GIF, WebP, MP4, WebM (Maks: 50MB)</p>
                            
                            <!-- Preview Gambar Saat Ini -->
                            <div class="mt-2 border rounded p-2 bg-gray-50">
                                <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                                @php
                                    $ext = strtolower(pathinfo($advertisement->image_path, PATHINFO_EXTENSION));
                                    $isVid = in_array($ext, ['mp4', 'webm', 'ogg']);
                                @endphp
                                @if($isVid)
                                    <video src="{{ asset('storage/' . $advertisement->image_path) }}" class="w-full h-auto max-h-32 object-contain rounded" controls muted loop playsinline></video>
                                @else
                                    <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="Current Banner" class="w-full h-auto max-h-32 object-contain rounded">
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="commission_type">Tipe Komisi</label>
                                <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="commission_type" name="commission_type">
                                    <option value="fixed" {{ $advertisement->commission_type == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                    <option value="percentage" {{ $advertisement->commission_type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="commission_amount">Nilai Komisi</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="commission_amount" name="commission_amount" type="number" step="0.01" min="0" value="{{ old('commission_amount', $advertisement->commission_amount) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Tgl Mulai</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" name="start_date" type="date" value="{{ old('start_date', $advertisement->start_date ? $advertisement->start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">Tgl Berakhir</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_date" name="end_date" type="date" value="{{ old('end_date', $advertisement->end_date ? $advertisement->end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 border-t pt-4 border-gray-200">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ $advertisement->is_active ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 text-sm font-bold">Status Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-7">Jika tidak dicentang, iklan ini tidak akan ditampilkan di website.</p>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.advertisements.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
