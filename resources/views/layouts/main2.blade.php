<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gemala Riau')</title>

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
                    }
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
                <a href="{{ url('/') }}" class="text-gray-900 hover:text-news-blue px-3 py-2 text-sm font-medium transition-colors {{ Request::is('/') ? 'text-news-blue' : '' }}">Home</a>
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
                <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-news-blue">Home</a>
                <a href="{{ url('/categories') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-news-blue">Kategori Berita</a>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Search News</h3>
                    <button id="closeSearchModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ url('/search') }}" method="GET">
                    <div class="flex gap-2">
                        <input type="text" name="q" placeholder="Search for news..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-news-blue">
                        <button type="submit" class="bg-gemala-gold text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition-colors font-semibold">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Breaking News Banner -->
    @if(isset($breakingNews))
    <div class="bg-news-red text-white py-2 px-4">
        <div class="max-w-7xl mx-auto flex items-center">
            <span class="breaking-badge bg-white text-news-red px-2 py-1 rounded text-xs font-bold mr-4">BREAKING</span>
            <div class="flex-1 overflow-hidden">
                <div class="animate-pulse">
                    <span class="text-sm">{{ $breakingNews }}</span>
                </div>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-6">
                    <h3 class="text-2xl font-bold gradient-text mb-4">Gemala Riau</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Gemala Riau adalah portal berita yang menyajikan informasi terkini dan terpercaya seputar Riau, dari masyarakat untuk masyarakat.
                    </p>
                    <!-- <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gemala-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gemala-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gemala-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div> -->
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} <a href="www.linkedin.com/in/tengku-said-aghil-zailani" >T. Said Aghil Zailani</a> | Gemala Riau News. All rights reserved.</p>
            </div>
        </div>
    </footer>

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