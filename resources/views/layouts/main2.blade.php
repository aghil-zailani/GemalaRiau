<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gemala Riau')</title>
    <meta name="description" content="@yield('meta_description', 'Gemala Riau adalah portal berita yang menyajikan informasi terkini dan terpercaya seputar Riau, dari masyarakat untuk masyarakat.')">
    <meta property="og:title" content="@yield('title', 'Gemala Riau News')">
    <meta property="og:description" content="@yield('meta_description', 'Portal berita terkini dan terpercaya seputar Riau.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="shortcut icon" href="{{ asset('logo/GemalaRiau.jpg') }}" type="image/png" />
    <link rel="stylesheet" href="{{ url('dist/assets/css/shared/iconly.css') }}" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'news-blue': '#1e3a8a', // Deep navy blue from logo
                        'news-gold': '#f59e0b', // Golden yellow from logo
                        'news-red': '#dc2626',
                        'news-dark': '#0f172a', // Darker navy
                        'gemala-blue': '#1e3a8a',
                        'gemala-gold': '#f59e0b',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'marquee': 'marquee 20s linear infinite',
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(100%)' },
                            '100%': { transform: 'translateX(-100%)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .news-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .breaking-badge {
            animation: pulse 2s infinite;
        }
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 20s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <a href="{{ url('/') }}" class="text-2xl font-bold gradient-text">Gemala Riau News</a>
        </div>
        <div class="hidden md:block ml-10">
            <div class="flex items-baseline space-x-8">
                <a href="{{ url('/') }}" class="text-gray-900 hover:text-news-blue px-3 py-2 text-sm font-medium transition-colors {{ Request::is('/') ? 'text-news-blue' : '' }}">Beranda</a>
                <a href="{{ url('/categories') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-news-blue">Kategori Berita</a>
            </div>
        </div>
    </div>

    <!-- Search Button -->
    <div class="flex items-center space-x-4">
        <button id="searchButton" class="text-gray-500 hover:text-news-blue">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
            </svg>
        </button>

        <!-- Mobile menu button -->
        <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
</div>

        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-news-blue">Beranda</a>
                <a href="{{ url('/categories') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-news-blue">Kategori Berita</a>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Cari Berita</h3>
                    <button id="closeSearchModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ url('/search') }}" method="GET">
                    <div class="flex gap-2">
                        <input type="text" name="q" placeholder="Ketik kata kunci berita..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-news-blue">
                        <button type="submit" class="bg-gemala-gold text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition-colors font-semibold">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Breaking News Banner -->
    @if(isset($breakingNews))
    <div class="bg-news-red text-white py-2 px-4 overflow-hidden">
        <div class="max-w-7xl mx-auto flex items-center">
            <span class="breaking-badge bg-white text-news-red px-2 py-1 rounded text-xs font-bold mr-4 flex-shrink-0">TERKINI</span>
            <div class="flex-1 overflow-hidden whitespace-nowrap">
                <span class="text-sm inline-block animate-marquee">{{ $breakingNews }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8">
                <div class="col-span-1">
                    <h3 class="text-2xl font-bold gradient-text mb-4">Gemala Riau</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Gemala Riau adalah portal berita yang menyajikan informasi terkini dan terpercaya seputar Riau, dari masyarakat untuk masyarakat.
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} <a href="https://aghil-zailani.github.io/Portopolio/" >T. Said Aghil Zailani</a> | Gemala Riau News. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-6 right-6 z-40 bg-gemala-blue text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-blue-800 transition-all opacity-0 invisible" title="Kembali ke atas">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // User menu toggle
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenu = document.getElementById('userMenu');
            
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
                
                // Close user menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
            
            // Search modal
            const searchButton = document.getElementById('searchButton');
            const searchModal = document.getElementById('searchModal');
            const closeSearchModal = document.getElementById('closeSearchModal');
            
            if (searchButton && searchModal && closeSearchModal) {
                searchButton.addEventListener('click', function() {
                    searchModal.classList.remove('hidden');
                });
                
                closeSearchModal.addEventListener('click', function() {
                    searchModal.classList.add('hidden');
                });
                
                // Close modal when clicking backdrop
                searchModal.addEventListener('click', function(event) {
                    if (event.target === searchModal) {
                        searchModal.classList.add('hidden');
                    }
                });
            }
            
            // Scroll to Top
            const scrollToTopBtn = document.getElementById('scrollToTop');
            if (scrollToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                        scrollToTopBtn.classList.add('opacity-100', 'visible');
                    } else {
                        scrollToTopBtn.classList.add('opacity-0', 'invisible');
                        scrollToTopBtn.classList.remove('opacity-100', 'visible');
                    }
                });
                
                scrollToTopBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe all news cards
            document.querySelectorAll('.news-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
            
            // Add click effects to buttons
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.querySelector('.ripple')) return;
                    
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>