@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Iklan</h2>
        <!-- Tombol untuk membuka Modal Tambah Iklan -->
        <button onclick="openModal('addAdModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            + Tambah Iklan
        </button>
    </div>

    <!-- Ringkasan Komisi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Total Iklan Aktif</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $totalAdsActive ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-bold uppercase">Total Estimasi Pendapatan (Fixed)</h3>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalCommissionFixed ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <!-- Alert Error Validasi -->
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Daftar Iklan -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Gambar Banner
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Info Iklan
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Posisi
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Komisi & Kontrak
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($advertisements as $ad)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-32 h-auto">
                                @php
                                    $ext = strtolower(pathinfo($ad->image_path, PATHINFO_EXTENSION));
                                    $isVid = in_array($ext, ['mp4', 'webm', 'ogg']);
                                @endphp
                                @if($isVid)
                                    <video class="w-full h-full rounded shadow" src="{{ asset('storage/' . $ad->image_path) }}" muted loop autoplay playsinline></video>
                                @else
                                    <img class="w-full h-full rounded shadow" src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" />
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $ad->title }}</p>
                        @if($ad->advertiser_name)
                            <p class="text-gray-600 text-xs mt-1">Klien: {{ $ad->advertiser_name }}</p>
                        @endif
                        <a href="{{ $ad->link_url }}" target="_blank" class="text-blue-500 hover:text-blue-800 text-xs truncate w-40 block mt-1" title="{{ $ad->link_url }}">
                            {{ $ad->link_url ?? 'Tidak ada link' }}
                        </a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold text-gray-900 leading-tight mb-1">
                            <span aria-hidden class="absolute inset-0 bg-gray-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{ Str::title(str_replace('_', ' ', $ad->position)) }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 font-bold">{{ $ad->formatted_commission }}</p>
                        @if($ad->start_date || $ad->end_date)
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $ad->start_date ? $ad->start_date->format('d M Y') : 'Mulai' }} - 
                                {{ $ad->end_date ? $ad->end_date->format('d M Y') : 'Selesai' }}
                            </p>
                            @if(!$ad->isInContractPeriod())
                                <span class="text-xs text-red-500 font-bold">Kontrak Habis</span>
                            @endif
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        @if($ad->is_active)
                            <span class="text-green-600 font-bold">Aktif</span>
                        @else
                            <span class="text-red-600 font-bold">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm flex gap-2">
                        <!-- Tombol Edit (bisa pakai halaman terpisah atau modal terpisah) -->
                        <a href="{{ route('admin.advertisements.edit', $ad->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        
                        <!-- Form Hapus -->
                        <form action="{{ route('admin.advertisements.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-3">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        Belum ada data iklan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODAL TAMBAH IKLAN ================= -->
<div id="addAdModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('addAdModal')"></div>

        <!-- Trik untuk center modal secara vertikal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Panel Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Tambah Iklan Baru
                        </h3>
                        
                        <!-- FORM -->
                        <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Judul -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Judul Iklan (Internal)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" type="text" required placeholder="Contoh: Banner Promo Akhir Tahun">
                            </div>

                            <!-- Nama Klien/Advertiser -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="advertiser_name">Nama Pengiklan / Klien</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="advertiser_name" name="advertiser_name" type="text" placeholder="Contoh: PT. ABC">
                            </div>

                            <!-- Gambar -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Gambar Banner</label>
                                <input class="w-full py-2 px-3 text-gray-700 bg-white border border-gray-300 rounded focus:outline-none focus:border-blue-500" id="image" name="image" type="file" accept="image/*,video/mp4,video/webm,video/ogg" required>
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF, WebP, MP4, WebM (Maks: 50MB)</p>
                            </div>

                            <!-- Link URL -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="link_url">URL Tujuan (Opsional)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="link_url" name="link_url" type="url" placeholder="https://contoh.com">
                            </div>

                            <!-- Posisi -->
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">Posisi Iklan</label>
                                <div class="relative">
                                    <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="position" name="position" required>
                                        <option value="top_header">Header Semua Halaman (Top Header)</option>
                                        <option value="home_middle">Tengah Beranda (Home Middle)</option>
                                        <option value="article_top">Atas Artikel (Article Top)</option>
                                        <option value="article_middle">Tengah Artikel (Article Middle)</option>
                                        <option value="article_bottom">Bawah Artikel (Article Bottom)</option>
                                        <option value="sidebar">Sidebar Samping</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Tipe Komisi -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="commission_type">Tipe Komisi</label>
                                    <div class="relative">
                                        <select class="block appearance-none w-full border border-gray-200 text-gray-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="commission_type" name="commission_type">
                                            <option value="fixed">Nominal (Rp)</option>
                                            <option value="percentage">Persentase (%)</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Nominal Komisi -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="commission_amount">Nilai Komisi</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="commission_amount" name="commission_amount" type="number" step="0.01" min="0" placeholder="0">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Tanggal Mulai -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Tanggal Mulai</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" name="start_date" type="date">
                                </div>
                                
                                <!-- Tanggal Berakhir -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">Tanggal Berakhir</label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end_date" name="end_date" type="date">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2 text-gray-700 text-sm font-bold">Status Aktif</span>
                                </label>
                            </div>

                            <!-- Modal Actions -->
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse -mx-4 -mb-4 mt-5">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan Iklan
                                </button>
                                <button type="button" onclick="closeModal('addAdModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Buka/Tutup Modal -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection