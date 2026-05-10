<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Gemala Riau')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    
    <link rel="shortcut icon" href="{{ url('logo/gr1.png') }}" type="image/png">

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    <div id="app" class="flex h-screen">
        <aside class="w-64 bg-slate-800 text-white flex-shrink-0 flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">Gemala Admin</a>
            </div>

            <nav class="flex-grow p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-4 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : 'hover:bg-slate-700' }}">
                            <i class="bi bi-speedometer2 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.articles.index') }}" class="flex items-center py-2 px-4 rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-slate-700' : 'hover:bg-slate-700' }}">
                            <i class="bi bi-newspaper mr-3"></i>
                            <span>Artikel Berita</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center py-2 px-4 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-slate-700' : 'hover:bg-slate-700' }}">
                            <i class="bi bi-tags mr-3"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-slate-700">
                <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" id="logoutButton" class="w-full flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="bi bi-power mr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-md h-16 flex items-center justify-between px-6">
                <h1 class="text-2xl font-semibold text-gray-800">
                    @yield('title')
                </h1>
                <div class="text-gray-600">
                    <span>Selamat datang, {{ Auth::user()->name }}!</span>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logoutButton').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah form submit langsung
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, submit form logout
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>
    
    <script>
        // Mengambil data session dari Laravel dan mengubahnya menjadi variabel JavaScript
        const sessionSuccess = @json(session('success'));
        const sessionError = @json(session('error'));
        const validationErrors = @json($errors->any());

        // Cek jika ada pesan sukses
        if (sessionSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: sessionSuccess,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Cek jika ada pesan error
        if (sessionError) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: sessionError,
            });
        }

        // Cek jika ada error validasi dari form
        if (validationErrors) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Terdapat beberapa kesalahan pada input Anda.<br>Silakan periksa kembali form.',
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>