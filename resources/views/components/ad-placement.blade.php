@props(['position'])

@if(isset($globalAds[$position]) && $globalAds[$position]->count() > 0)
    <div class="relative w-full max-w-full overflow-hidden ad-carousel-container" data-position="{{ $position }}">
        <!-- Carousel Wrapper -->
        <div class="flex transition-transform duration-500 ease-in-out ad-carousel-track">
            @foreach($globalAds[$position] as $ad)
                <div class="w-full flex-shrink-0 relative group inline-block text-center px-2">
                    <span class="text-[10px] sm:text-xs text-gray-400 block mb-1 uppercase tracking-wider">
                        Advertisement
                        @if($ad->advertiser_name)
                            &bull; Sponsored by {{ $ad->advertiser_name }}
                        @endif
                    </span>
                    
                    <a href="{{ $ad->link_url ?? '#' }}" @if($ad->link_url) target="_blank" @endif class="block relative w-full h-full flex justify-center">
                        @php
                            $extension = pathinfo($ad->image_path, PATHINFO_EXTENSION);
                            $isVideo = in_array(strtolower($extension), ['mp4', 'webm', 'ogg']);
                        @endphp
                        
                        @if($isVideo)
                            <video src="{{ asset('storage/' . $ad->image_path) }}" autoplay loop muted playsinline class="mx-auto rounded-lg shadow-sm w-full max-h-64 object-cover"></video>
                        @else
                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="mx-auto rounded-lg shadow-sm w-full max-h-64 object-cover">
                        @endif
                    </a>

                    @if($ad->commission_amount > 0)
                        <div class="absolute bottom-2 right-4 bg-black/70 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity flex items-center pointer-events-none z-10">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Ad Placement
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Carousel Controls (Only show if more than 1 ad) -->
        @if($globalAds[$position]->count() > 1)
            <button class="absolute top-1/2 left-2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full z-10 prev-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button class="absolute top-1/2 right-2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full z-10 next-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-1 z-10 dots-container">
                @foreach($globalAds[$position] as $index => $ad)
                    <button class="w-2 h-2 rounded-full bg-white/50 hover:bg-white dot-btn" data-index="{{ $index }}"></button>
                @endforeach
            </div>
        @endif
    </div>

    @if($globalAds[$position]->count() > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const containers = document.querySelectorAll('.ad-carousel-container[data-position="{{ $position }}"]');
                containers.forEach(container => {
                    const track = container.querySelector('.ad-carousel-track');
                    const slides = track.children;
                    const prevBtn = container.querySelector('.prev-btn');
                    const nextBtn = container.querySelector('.next-btn');
                    const dots = container.querySelectorAll('.dot-btn');
                    
                    let currentIndex = 0;
                    const slideCount = slides.length;
                    let slideInterval;

                    function updateSlider() {
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                        dots.forEach((dot, index) => {
                            if (index === currentIndex) {
                                dot.classList.replace('bg-white/50', 'bg-white');
                            } else {
                                dot.classList.replace('bg-white', 'bg-white/50');
                            }
                        });
                    }

                    function nextSlide() {
                        currentIndex = (currentIndex + 1) % slideCount;
                        updateSlider();
                    }

                    function prevSlide() {
                        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
                        updateSlider();
                    }

                    if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetInterval(); });
                    if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetInterval(); });
                    
                    dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            currentIndex = index;
                            updateSlider();
                            resetInterval();
                        });
                    });

                    function startInterval() {
                        slideInterval = setInterval(nextSlide, 5000); // Ganti tiap 5 detik
                    }

                    function resetInterval() {
                        clearInterval(slideInterval);
                        startInterval();
                    }

                    // Initialize
                    updateSlider();
                    startInterval();
                });
            });
        </script>
    @endif
@endif
