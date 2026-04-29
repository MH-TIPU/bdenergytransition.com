<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Energy Transition</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Tailwind CSS (via CDN for quick prototyping, though local vite is better) -->
    <!-- Let's use Vite since we installed Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        /* Hero Slider Custom Styles */
        .swiper-hero {
            width: 100%;
            height: auto;
        }

        .swiper-hero .swiper-slide {
            position: relative;
        }

        /* Roadmap Timeline */
        .roadmap {
            position: relative;
        }

        .roadmap-node {
            position: absolute;
            transform: translate(-50%, -50%) scale(0.92);
            opacity: 0;
            transition: opacity 450ms ease, transform 450ms ease, box-shadow 450ms ease;
            will-change: transform, opacity;
        }

        .roadmap-flag {
            position: absolute;
            left: 50%;
            top: calc(-1 * var(--lift, 54px));
            transform: translate(-50%, -50%);
        }

        .roadmap-stem {
            position: absolute;
            left: 50%;
            top: calc(-1 * var(--lift, 54px));
            width: 3px;
            height: var(--lift, 54px);
            transform: translateX(-50%);
            background: #e2e8f0;
            border-radius: 9999px;
        }

        .roadmap-label {
            position: absolute;
            left: 50%;
            top: calc(-1 * var(--lift, 54px) - 34px);
            transform: translate(-50%, -50%);
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .roadmap-label {
                max-width: calc(100vw - 3.5rem);
                white-space: normal;
                text-align: center;
            }

            .roadmap-label > span {
                max-width: 100%;
            }
        }

        .roadmap-bubble {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            border: 4px solid currentColor;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            font-weight: 800;
            color: #111827;
            pointer-events: none;
        }

        .roadmap-bubble-icon {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 9999px;
            display: block;
        }

        .roadmap-node.is-active .roadmap-bubble {
            transform: scale(1.06);
            box-shadow: 0 18px 25px -5px rgba(0, 0, 0, 0.12);
        }

        .roadmap-node.is-visible {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .roadmap-node.is-active {
            transform: translate(-50%, -50%) scale(1.08);
            z-index: 30;
        }

        .roadmap-card {
            position: absolute;
            width: min(560px, calc(100vw - 2rem));
            max-height: calc(100vh - 2.5rem);
            overflow: auto;
            opacity: 0;
            transform: translateY(8px);
            pointer-events: none;
            transition: opacity 200ms ease, transform 200ms ease;
            z-index: 60;
        }

        .roadmap-card.is-open {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Quotes section */
        .quotes-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1466611653911-95081537e5b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            /* Placeholder background */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 6rem 0;
        }

        .quote-card {
            background: white;
            border-radius: 1rem;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .swiper-quotes {
            padding-bottom: 3rem;
        }

        /* Tab Navigation Styles */
        .tab-menu {
            border-bottom: 1px solid #e5e7eb;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .tab-link {
            display: inline-block;
            padding: 1rem 2rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            bottom: -1px;
        }

        .tab-link:hover {
            color: #5b21b6;
        }

        .tab-link.active {
            color: #5b21b6;
            border-bottom-color: #5b21b6;
        }
    </style>
</head>

<body class="antialiased text-gray-800">

    <!-- Header with Tabbed Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-6">
            <div class="pt-6">
                <div class="text-center mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-purple-800 tracking-tight uppercase">
                        GLOBAL ENERGY SHOCKS AND BANGLADESH'S LNG RELIANCE
                    </h1>
                </div>
                <nav class="tab-menu">
                    <a href="#bangladesh-lng" class="tab-link active">Bangladesh's LNG</a>
                    <a href="#global-energy" class="tab-link">Global Energy Shocks</a>
                    <a href="#renewable-shield" class="tab-link">Renewable as Shield</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- HERO SLIDER SECTION -->
    <section class="bg-white pt-34">
      

        <div class="w-full">
            <div class="swiper swiper-hero w-full bg-white relative group overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse($heroNews as $news)
                        <div class="swiper-slide w-full bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 w-full">
                                <!-- Left Content -->
                                <div class="flex flex-col justify-center w-full order-2 md:order-1">
                                    <!-- Inner container aligned to the main container's left edge -->
                                    <div class="w-full ml-auto px-6 md:px-8 lg:px-12 py-12 md:py-16"
                                        style="max-width: 768px;">
                                        <!-- Show first category or default tag -->
                                        @php
                                            $categoryNames = $news->categories->pluck('name')->filter()->values();
                                        @endphp
                                        <span class="text-purple-800 text-xs md:text-sm font-bold uppercase tracking-widest mb-4 block">
                                            {{ $categoryNames->isNotEmpty() ? $categoryNames->join(' • ') : 'Energy Blog' }}
                                        </span>
                                        <h1 class="text-3xl md:text-4xl lg:text-[2.75rem] xl:text-[3.25rem] font-extrabold text-gray-900 mb-6 leading-tight tracking-tight"
                                            style="font-family: Georgia, serif;">
                                            @if($news->src_link)
                                                <a href="{{ $news->src_link }}" target="_blank" class="hover:text-purple-800 transition-colors">{{ $news->title }}</a>
                                            @else
                                                {{ $news->title }}
                                            @endif
                                        </h1>
                                        <p
                                            class="text-gray-600 text-sm md:text-base lg:text-lg leading-relaxed mb-8 max-w-xl line-clamp-3">
                                            {{ $news->excerpt ?? strip_tags($news->description) }}
                                        </p>
                                        @if($news->src_link)
                                            <a href="{{ $news->src_link }}" target="_blank"
                                                class="text-purple-800 font-bold uppercase tracking-wider text-xs md:text-sm hover:underline flex items-center w-max">
                                                Read Full Story
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <!-- Right Image -->
                                <div class="w-full order-1 md:order-2 flex items-center justify-center bg-white">
                                    <img src="{{ $news->feature_image ? asset('storage/' . $news->feature_image) : 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' }}"
                                        alt="{{ $news->title }}"
                                    class="w-full h-full max-h-[80vh] object-contain">
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback if no hero news -->
                        <div class="swiper-slide w-full bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 w-full min-h-[70vh] md:min-h-[80vh]">
                                <div
                                    class="flex flex-col justify-center px-8 md:px-20 py-16 h-full w-full order-2 md:order-1">
                                    <span
                                        class="text-purple-800 text-sm font-bold uppercase tracking-widest mb-4 block">Welcome</span>
                                    <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight"
                                        style="font-family: Georgia, serif;">
                                        <a href="#" class="hover:text-purple-800 transition-colors">BD Energy Transition Platform</a>
                                    </h1>
                                    <p class="text-gray-600 text-lg leading-relaxed max-w-lg">
                                        Tracking the shift towards sustainable and renewable energy sources in Bangladesh
                                        amid global energy shocks.
                                    </p>
                                </div>
                                <div class="h-full w-full order-1 md:order-2">
                                    <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                                        alt="Fallback" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Custom Navigation Arrows positioned absolutely to edges -->
                {{-- <div
                    class="swiper-button-prev text-gray-300! hover:text-purple-800! transition left-4! md:left-8! drop-shadow-md">
                </div>
                <div
                    class="swiper-button-next text-gray-300! hover:text-purple-800! transition right-4! md:right-8! drop-shadow-md">
                </div> --}}
            </div>
        </div>
    </section>

    <!-- TIMELINE SECTION -->
    <section id="timeline" class="py-24 bg-linear-to-b from-[#f8fafc] to-[#f1f5f9] overflow-hidden relative">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-purple-800 tracking-widest uppercase mb-2">Global Events</h2>
            </div>
        </div>

        <div class="w-full md:w-2/3 mx-auto px-4">
            <div class="roadmap w-full">
                <div class="relative w-full p-4 md:p-10">
                    <div class="relative w-full h-140 md:h-105" data-roadmap>
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 420" preserveAspectRatio="none" aria-hidden="true">
                            <defs>
                                <linearGradient id="roadmapGrad" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%" stop-color="#5b21b6" stop-opacity="1" />
                                    <stop offset="100%" stop-color="#5b21b6" stop-opacity="1" />
                                </linearGradient>
                            </defs>

                            <path id="roadmapPathBase" d="M40,280 C180,110 320,390 460,220 C600,60 740,360 880,160" fill="none" stroke="#e2e8f0" stroke-width="20" stroke-linecap="round" />
                            <path id="roadmapPathProgress" d="M40,280 C180,110 320,390 460,220 C600,60 740,360 880,160" fill="none" stroke="url(#roadmapGrad)" stroke-width="20" stroke-linecap="round" />
                        </svg>

                        <div class="absolute inset-0" data-roadmap-nodes></div>
                        <div class="roadmap-card" data-roadmap-card></div>
                    </div>

                    @php
                        $timelineMilestonesJson = ($timelineMilestones ?? collect())
                            ->values()
                            ->map(function ($event) {
                                return [
                                    'id' => $event->id,
                                    'date' => optional($event->event_date)->toDateString(),
                                    'year' => optional($event->event_date)->format('Y'),
                                    'label' => optional($event->event_date)->format('M Y'),
                                    'iconName' => $event->icon?->name,
                                    'iconImage' => $event->icon?->image ? asset('storage/' . $event->icon->image) : null,
                                    'globalTitle' => $event->global_event_title,
                                    'globalExcerpt' => $event->global_event_excerpt,
                                    'globalLink' => $event->global_event_link,
                                    'impactTitle' => $event->impact_title,
                                    'impactExcerpt' => $event->impact_excerpt,
                                    'impactLink' => $event->impact_link,
                                ];
                            })
                            ->all();
                    @endphp

                    <script type="application/json" id="timeline-milestones-json">@json($timelineMilestonesJson)</script>
                </div>
            </div>
        </div>
    </section>

    <!-- QUOTES CAROUSEL SECTION -->
    <section class="quotes-section">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-sm font-bold text-white tracking-widest uppercase mb-2">Expert Opinions</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-white">Why We Need To Expand Renewable Energy?</h3>
            </div>

            <div class="swiper swiper-quotes max-w-6xl mx-auto">
                <div class="swiper-wrapper">
                    @forelse($quotes as $quote)
                        <div class="swiper-slide px-4">
                            <div class="quote-card">
                                <svg class="w-12 h-12 text-purple-200 mx-auto mb-6" fill="currentColor" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <path
                                        d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                </svg>
                                <p class="text-xl md:text-2xl font-semibold text-gray-800 italic mb-8">
                                    "{{ $quote->quote_text }}"</p>
                                <div class="flex items-center justify-center space-x-4">
                                    @if($quote->author_image)
                                        <img src="{{ asset('storage/' . $quote->author_image) }}" alt="{{ $quote->author_name }}"
                                            class="w-16 h-16 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-purple-800 font-bold text-xl">
                                            {{ substr($quote->author_name, 0, 1) }}</div>
                                    @endif
                                    <div class="text-left">
                                        <h5 class="font-bold text-gray-900">{{ $quote->author_name }}</h5>
                                        <p class="text-sm text-gray-500 uppercase tracking-wide">
                                            {{ $quote->author_designation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback quote -->
                        <div class="swiper-slide px-4">
                            <div class="quote-card">
                                <p class="text-xl md:text-2xl font-semibold text-gray-800 italic mb-8">"Renewable energy
                                    shouldn't be a missed golden opportunity for Bangladesh."</p>
                                <div class="flex items-center justify-center space-x-4">
                                    <h5 class="font-bold text-gray-900">Energy Expert</h5>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                {{-- <div class="swiper-button-next" style="color: white;"></div>
                <div class="swiper-button-prev" style="color: white;"></div> --}}
            </div>
        </div>
    </section>

    <!-- FEATURED SLIDER SECTION (after Expert Opinions) -->
    <section class=" bg-white">
        <div class="w-full">
            <div class="swiper swiper-featured w-full bg-white relative group overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse(($featuredNews ?? collect()) as $news)
                        <div class="swiper-slide w-full bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 w-full ">
                                <!-- Left Image -->
                                <div class="w-full order-1 md:order-1 flex items-center justify-center bg-white">
                                    <img src="{{ $news->feature_image ? asset('storage/' . $news->feature_image) : 'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' }}"
                                        alt="{{ $news->title }}"
                                        class="w-full h-full max-h-[70vh] object-contain">
                                </div>

                                <!-- Right Content -->
                                <div class="flex flex-col justify-center w-full order-2 md:order-2">
                                    <div class="w-full ml-auto px-6 md:px-8 lg:px-12 py-12 md:py-16" style="max-width: 768px;">
                                        @php
                                            $categoryNames = $news->categories->pluck('name')->filter()->values();
                                        @endphp
                                        <span class="text-purple-800 text-xs md:text-sm font-bold uppercase tracking-widest mb-4 block">
                                            {{ $categoryNames->isNotEmpty() ? $categoryNames->join(' • ') : 'Energy Blog' }}
                                        </span>
                                        <h2 class="text-3xl md:text-4xl lg:text-[2.25rem] xl:text-[2.75rem] font-extrabold text-gray-900 mb-6 leading-tight tracking-tight" style="font-family: Georgia, serif;">
                                            @if($news->src_link)
                                                <a href="{{ $news->src_link }}" target="_blank" class="hover:text-purple-800 transition-colors">{{ $news->title }}</a>
                                            @else
                                                {{ $news->title }}
                                            @endif
                                        </h2>
                                        <p class="text-gray-600 text-sm md:text-base lg:text-lg leading-relaxed mb-8 max-w-xl line-clamp-3">
                                            {{ $news->excerpt ?? strip_tags($news->description) }}
                                        </p>
                                        @if($news->src_link)
                                            <a href="{{ $news->src_link }}" target="_blank"
                                                class="text-purple-800 font-bold uppercase tracking-wider text-xs md:text-sm hover:underline flex items-center w-max">
                                                Read Full Story
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide w-full bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 w-full min-h-[60vh] md:min-h-[70vh]">
                                
                                <div class="h-full w-full order-1 md:order-1">
                                    <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                                        alt="Featured placeholder" class="w-full h-full object-cover">
                                </div>
                                
                                <div class="flex flex-col justify-center px-8 md:px-20 py-16 h-full w-full order-2 md:order-2">
                                    <span class="text-purple-800 text-sm font-bold uppercase tracking-widest mb-4 block">Featured</span>
                                    <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight" style="font-family: Georgia, serif;">
                                        No featured news yet
                                    </h2>
                                    <p class="text-gray-600 text-lg leading-relaxed max-w-lg">Enable “Show in Featured” on a News Item from the admin dashboard to display it here.</p>
                                </div>
                                
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- <div class="swiper-button-prev text-gray-300! hover:text-purple-800! transition left-4! md:left-8! drop-shadow-md"></div>
                <div class="swiper-button-next text-gray-300! hover:text-purple-800! transition right-4! md:right-8! drop-shadow-md"></div> --}}
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-4">BD Energy Transition</h2>
            <p class="text-gray-400 mb-8 max-w-2xl mx-auto">Tracking the shift towards sustainable and renewable energy
                sources in Bangladesh amid global energy shocks.</p>
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} BD Energy Transition. All rights reserved.</p>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Initialize Hero Slider
        var swiperHero = new Swiper(".swiper-hero", {
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-hero .swiper-button-next",
                prevEl: ".swiper-hero .swiper-button-prev",
            },
        });

        // Initialize Quotes Slider
        var swiperQuotes = new Swiper(".swiper-quotes", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-quotes .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-quotes .swiper-button-next",
                prevEl: ".swiper-quotes .swiper-button-prev",
            },
        });

        // Initialize Featured Slider
        var swiperFeatured = new Swiper(".swiper-featured", {
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: {
                delay: 5500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-featured .swiper-button-next",
                prevEl: ".swiper-featured .swiper-button-prev",
            },
        });

        // Tab Navigation Handler
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabLinks.forEach(tab => tab.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>