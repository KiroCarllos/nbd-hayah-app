@extends('layouts.app')

@section('title', 'الرئيسية - نبض الحياة')

@section('content')
    <!-- Main Slider - Enhanced Large Size -->
    @if ($sliders->count() > 0)
        <div id="mainSlider" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="6000">
            <style>
                /* Enhanced Large Slider Styles */
                #mainSlider {
                    height: 100vh;
                    /* Full viewport height */
                    max-height: 800px;
                    /* Maximum height for very large screens */
                    min-height: 600px;
                    /* Minimum height for smaller screens */
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                }

                #mainSlider .carousel-item {
                    height: 100vh;
                    max-height: 800px;
                    min-height: 600px;
                    transition: transform 1s ease-in-out;
                    position: relative;
                }

                #mainSlider .carousel-item img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    /* Ensures full coverage while maintaining aspect ratio */
                    object-position: center center !important;
                    filter: brightness(0.8);
                    /* Slight darkening for better text readability */
                }

                #mainSlider .carousel-caption {
                    bottom: 25%;
                    left: 10%;
                    right: 10%;
                    text-align: center;
                    z-index: 10;
                }

                #mainSlider .carousel-caption .bg-dark {
                    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%) !important;
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 20px !important;
                    padding: 2rem !important;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                }

                #mainSlider .carousel-control-prev,
                #mainSlider .carousel-control-next {
                    width: 8%;
                    height: 100%;
                    background: linear-gradient(90deg, rgba(0, 0, 0, 0.3) 0%, transparent 100%);
                    border: none;
                    opacity: 0.8;
                    transition: all 0.3s ease;
                }

                #mainSlider .carousel-control-next {
                    background: linear-gradient(-90deg, rgba(0, 0, 0, 0.3) 0%, transparent 100%);
                }

                #mainSlider .carousel-control-prev:hover,
                #mainSlider .carousel-control-next:hover {
                    opacity: 1;
                    background: linear-gradient(90deg, rgba(0, 0, 0, 0.5) 0%, transparent 100%);
                }

                #mainSlider .carousel-control-next:hover {
                    background: linear-gradient(-90deg, rgba(0, 0, 0, 0.5) 0%, transparent 100%);
                }

                #mainSlider .carousel-control-prev-icon,
                #mainSlider .carousel-control-next-icon {
                    width: 3rem;
                    height: 3rem;
                    background-size: 100% 100%;
                    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
                }

                #mainSlider .carousel-indicators {
                    bottom: 5%;
                    margin-bottom: 0;
                }

                #mainSlider .carousel-indicators button {
                    width: 15px;
                    height: 15px;
                    border-radius: 50%;
                    margin: 0 8px;
                    background: rgba(255, 255, 255, 0.5);
                    border: 2px solid rgba(255, 255, 255, 0.8);
                    transition: all 0.3s ease;
                }

                #mainSlider .carousel-indicators button.active {
                    background: #ff4d57;
                    border-color: #fff;
                    transform: scale(1.2);
                    box-shadow: 0 0 10px rgba(255, 77, 87, 0.5);
                }

                /* Fallback gradient backgrounds - Enhanced */
                #mainSlider .fallback-bg {
                    width: 100% !important;
                    height: 100% !important;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                    overflow: hidden;
                }

                #mainSlider .fallback-bg::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
                    z-index: 1;
                }

                #mainSlider .fallback-bg i {
                    font-size: 12rem !important;
                    opacity: 0.2 !important;
                    z-index: 2;
                    position: relative;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    #mainSlider {
                        height: 70vh;
                        max-height: 500px;
                        min-height: 400px;
                    }

                    #mainSlider .carousel-item {
                        height: 70vh;
                        max-height: 500px;
                        min-height: 400px;
                    }

                    #mainSlider .carousel-caption {
                        bottom: 15%;
                        left: 5%;
                        right: 5%;
                    }

                    #mainSlider .carousel-caption .bg-dark {
                        padding: 1.5rem !important;
                        border-radius: 15px !important;
                    }

                    #mainSlider .carousel-caption h2 {
                        font-size: 1.8rem !important;
                    }

                    #mainSlider .carousel-caption p {
                        font-size: 1rem !important;
                    }

                    #mainSlider .carousel-control-prev,
                    #mainSlider .carousel-control-next {
                        width: 12%;
                    }

                    #mainSlider .fallback-bg i {
                        font-size: 8rem !important;
                    }
                }

                @media (max-width: 576px) {
                    #mainSlider {
                        height: 60vh;
                        max-height: 400px;
                        min-height: 350px;
                    }

                    #mainSlider .carousel-item {
                        height: 60vh;
                        max-height: 400px;
                        min-height: 350px;
                    }

                    #mainSlider .carousel-caption h2 {
                        font-size: 1.5rem !important;
                    }

                    #mainSlider .carousel-caption p {
                        font-size: 0.9rem !important;
                        display: none;
                        /* Hide description on very small screens */
                    }

                    #mainSlider .fallback-bg i {
                        font-size: 6rem !important;
                    }
                }
            </style>
            <div class="carousel-indicators">
                @foreach ($sliders as $index => $slider)
                    <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="true"
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="position-relative w-100 h-100">
                            @if (file_exists(public_path('storage/' . $slider->image)))
                                <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100 h-100"
                                    alt="{{ $slider->title }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    style="object-fit: cover; object-position: center;">
                            @else
                                <!-- Enhanced Fallback gradient background -->
                                <div class="fallback-bg"
                                    style="background: linear-gradient(135deg,
                                    @if ($index % 4 == 0) #ff4d57 0%, #31a354 50%, #007bff 100%
                                    @elseif($index % 4 == 1) #31a354 0%, #ff4d57 50%, #6f42c1 100%
                                    @elseif($index % 4 == 2) #007bff 0%, #ff4d57 50%, #31a354 100%
                                    @else #6f42c1 0%, #31a354 50%, #ff4d57 100% @endif
                                    );">
                                    <i class="bi bi-heart-fill text-white"></i>
                                </div>
                            @endif

                            <!-- Enhanced Caption with better positioning -->
                            <div class="carousel-caption d-block">
                                <div class="bg-dark bg-opacity-50 p-4 rounded">
                                    <h2 class="display-4 fw-bold text-white mb-3"
                                        style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                                        {{ $slider->title }}
                                    </h2>
                                    @if ($slider->description)
                                        <p class="lead text-white mb-4"
                                            style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-size: 1.2rem;">
                                            {{ $slider->description }}
                                        </p>
                                    @endif
                                    @if ($slider->button_text && $slider->button_link)
                                        <a href="{{ $slider->button_link }}"
                                            class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg"
                                            style="font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;">
                                            <i class="bi bi-arrow-left-circle me-2"></i>
                                            {{ $slider->button_text }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Overlay for better text readability -->
                            <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="background: linear-gradient(45deg, rgba(0,0,0,0.3) 0%, transparent 50%, rgba(0,0,0,0.2) 100%); pointer-events: none;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Enhanced Navigation Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">السابق</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">التالي</span>
            </button>

            <!-- Progress Bar -->
            <div class="position-absolute bottom-0 start-0 w-100"
                style="height: 4px; background: rgba(255,255,255,0.2); z-index: 15;">
                <div id="sliderProgress" class="h-100 bg-primary" style="width: 0%; transition: width 6s linear;"></div>
            </div>
        </div>

        <!-- Enhanced JavaScript for better slider experience -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('mainSlider');
                const progressBar = document.getElementById('sliderProgress');

                if (slider && progressBar) {
                    let currentSlide = 0;
                    const totalSlides = {{ $sliders->count() }};
                    let progressInterval;

                    // Initialize progress bar
                    function startProgress() {
                        progressBar.style.width = '0%';
                        progressBar.style.transition = 'width 6s linear';
                        setTimeout(() => {
                            progressBar.style.width = '100%';
                        }, 100);
                    }

                    // Reset progress bar
                    function resetProgress() {
                        progressBar.style.transition = 'none';
                        progressBar.style.width = '0%';
                    }

                    // Start initial progress
                    startProgress();

                    // Handle slide events
                    slider.addEventListener('slide.bs.carousel', function(e) {
                        resetProgress();
                        currentSlide = e.to;
                    });

                    slider.addEventListener('slid.bs.carousel', function(e) {
                        startProgress();
                    });

                    // Pause on hover
                    slider.addEventListener('mouseenter', function() {
                        progressBar.style.animationPlayState = 'paused';
                    });

                    slider.addEventListener('mouseleave', function() {
                        progressBar.style.animationPlayState = 'running';
                    });

                    // Touch/swipe support enhancement
                    let startX = 0;
                    let startY = 0;

                    slider.addEventListener('touchstart', function(e) {
                        startX = e.touches[0].clientX;
                        startY = e.touches[0].clientY;
                    });

                    slider.addEventListener('touchend', function(e) {
                        if (!startX || !startY) return;

                        let endX = e.changedTouches[0].clientX;
                        let endY = e.changedTouches[0].clientY;

                        let diffX = startX - endX;
                        let diffY = startY - endY;

                        // Only trigger if horizontal swipe is more significant than vertical
                        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                            if (diffX > 0) {
                                // Swipe left - next slide
                                bootstrap.Carousel.getInstance(slider).next();
                            } else {
                                // Swipe right - previous slide
                                bootstrap.Carousel.getInstance(slider).prev();
                            }
                        }

                        startX = 0;
                        startY = 0;
                    });
                }

                // Preload next images for better performance
                const images = slider.querySelectorAll('img[loading="lazy"]');
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.loading = 'eager';
                            observer.unobserve(img);
                        }
                    });
                });

                images.forEach(img => imageObserver.observe(img));
            });
        </script>
    @else
        <!-- Default Hero Section if no sliders -->
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="bg-primary text-white rounded p-5 text-center">
                        <h1 class="display-4 mb-3">مرحباً بك في نبض الحياة</h1>
                        <p class="lead">منصة التبرعات الخيرية لمساعدة المحتاجين وإحداث فرق في المجتمع</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg mt-3">
                                <i class="bi bi-person-plus me-2"></i>
                                انضم إلينا الآن
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container">

        <!-- Priority Campaigns Slider - Enhanced Auto Play Cards -->
        @if ($priorityCampaigns->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            الحملات المميزة
                        </h2>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2">{{ $priorityCampaigns->count() }} حملة</span>
{{--                            <div class="btn-group" role="group">--}}
{{--                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="pauseCarousel()">--}}
{{--                                    <i class="bi bi-pause-fill" id="pauseIcon"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="playCarousel()">--}}
{{--                                    <i class="bi bi-play-fill" id="playIcon"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                    </div>

                    <div id="priorityCampaignsCarousel" class="carousel slide" data-bs-ride="carousel"
                        data-bs-interval="4000">
                        <style>
                            /* Enhanced Priority Campaigns Slider Styles */
                            #priorityCampaignsCarousel {
                                position: relative;
                                overflow: hidden;
                                border-radius: 20px;
                                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                padding: 20px;
                            }

                            #priorityCampaignsCarousel .carousel-item {
                                transition: transform 0.8s ease-in-out;
                                padding: 10px;
                            }

                            #priorityCampaignsCarousel .card {
                                border: none;
                                border-radius: 20px;
                                overflow: hidden;
                                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                                transition: all 0.3s ease;
                                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                                position: relative;
                            }

                            #priorityCampaignsCarousel .card:hover {
                                transform: translateY(-5px);
                                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
                            }

                            #priorityCampaignsCarousel .card::before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                right: 0;
                                height: 4px;
                                background: linear-gradient(90deg, #ff4d57 0%, #31a354 50%, #007bff 100%);
                                z-index: 1;
                            }

                            #priorityCampaignsCarousel .card-img-container {
                                position: relative;
                                overflow: hidden;
                                border-radius: 15px;
                                height: 250px;
                            }

                            #priorityCampaignsCarousel .card-img-container img {
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                                transition: transform 0.3s ease;
                            }

                            #priorityCampaignsCarousel .card:hover .card-img-container img {
                                transform: scale(1.05);
                            }

                            #priorityCampaignsCarousel .card-img-overlay {
                                position: absolute;
                                top: 10px;
                                right: 10px;
                                color: white;
                                padding: 5px 10px;
                                border-radius: 15px;
                                font-size: 0.8rem;
                                font-weight: 600;
                                z-index: 2;
                            }

                            #priorityCampaignsCarousel .card-body {
                                padding: 1.5rem;
                                position: relative;
                            }

                            #priorityCampaignsCarousel .card-title {
                                font-size: 1.3rem;
                                font-weight: 700;
                                color: #2c3e50;
                                margin-bottom: 0.8rem;
                                line-height: 1.4;
                            }

                            #priorityCampaignsCarousel .card-text {
                                color: #6c757d;
                                font-size: 0.95rem;
                                line-height: 1.6;
                                margin-bottom: 1.2rem;
                            }

                            #priorityCampaignsCarousel .progress {
                                height: 8px;
                                border-radius: 10px;
                                background: #e9ecef;
                                overflow: hidden;
                                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
                            }

                            #priorityCampaignsCarousel .progress-bar {
                                background: linear-gradient(90deg, #31a354 0%, #28a745 100%);
                                border-radius: 10px;
                                transition: width 0.6s ease;
                                position: relative;
                            }

                            #priorityCampaignsCarousel .progress-bar::after {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
                                animation: shimmer 2s infinite;
                            }

                            @keyframes shimmer {
                                0% {
                                    transform: translateX(-100%);
                                }

                                100% {
                                    transform: translateX(100%);
                                }
                            }

                            #priorityCampaignsCarousel .btn-primary {
                                background: linear-gradient(135deg, #ff4d57 0%, #e63946 100%);
                                border: none;
                                border-radius: 25px;
                                padding: 10px 20px;
                                font-weight: 600;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 15px rgba(255, 77, 87, 0.3);
                            }

                            #priorityCampaignsCarousel .btn-primary:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 8px 25px rgba(255, 77, 87, 0.4);
                                background: linear-gradient(135deg, #e63946 0%, #dc2626 100%);
                            }

                            #priorityCampaignsCarousel .carousel-control-prev,
                            #priorityCampaignsCarousel .carousel-control-next {
                                width: 50px;
                                height: 50px;
                                top: 50%;
                                transform: translateY(-50%);
                                background: rgba(255, 255, 255, 0.9);
                                border-radius: 50%;
                                border: 2px solid #e9ecef;
                                opacity: 0.8;
                                transition: all 0.3s ease;
                            }

                            #priorityCampaignsCarousel .carousel-control-prev {
                                left:-0px;
                            }

                            #priorityCampaignsCarousel .carousel-control-next {
                                right: 0px;
                            }

                            #priorityCampaignsCarousel .carousel-control-prev:hover,
                            #priorityCampaignsCarousel .carousel-control-next:hover {
                                opacity: 1;
                                background: #ffffff;
                                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                                transform: translateY(-50%) scale(1.1);
                            }

                            #priorityCampaignsCarousel .carousel-control-prev-icon,
                            #priorityCampaignsCarousel .carousel-control-next-icon {
                                width: 20px;
                                height: 20px;
                                background-size: 100% 100%;
                                filter: invert(1);
                            }

                            /* Responsive adjustments */
                            @media (max-width: 768px) {
                                #priorityCampaignsCarousel {
                                    padding: 15px;
                                    border-radius: 15px;
                                }

                                #priorityCampaignsCarousel .card-img-container {
                                    height: 200px;
                                }

                                #priorityCampaignsCarousel .card-body {
                                    padding: 1rem;
                                }

                                #priorityCampaignsCarousel .carousel-control-prev,
                                #priorityCampaignsCarousel .carousel-control-next {
                                    width: 40px;
                                    height: 40px;
                                }

                                #priorityCampaignsCarousel .carousel-control-prev {
                                    left: -20px;
                                }

                                #priorityCampaignsCarousel .carousel-control-next {
                                    right: -20px;
                                }
                            }
                        </style>
                        <div class="carousel-inner">
                            @foreach ($priorityCampaigns as $index => $campaign)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="card">
                                        <!-- Enhanced Card Image Container -->
                                        <div class="card-img-container">
                                            @if ($campaign->images && count($campaign->images) > 0)
                                                <img src="{{ asset('storage/' . $campaign->images[0]) }}"
                                                    alt="{{ $campaign->title }}"
                                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100"
                                                    style="background: linear-gradient(135deg,
                                                     @if ($index % 3 == 0) #ff4d57 0%, #31a354 100%
                                                     @elseif($index % 3 == 1) #31a354 0%, #007bff 100%
                                                     @else #007bff 0%, #ff4d57 100% @endif
                                                     );">
                                                    <i class="bi bi-heart-fill text-white"
                                                        style="font-size: 4rem; opacity: 0.8;"></i>
                                                </div>
                                            @endif

                                            <!-- Priority Badge -->
                                            <div class="card-img-overlay">
                                                <i class="bi bi-star-fill me-1"></i>مميزة
                                            </div>
                                        </div>

                                        <!-- Enhanced Card Body -->
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $campaign->title }}</h5>
                                            <p class="card-text">{{ Str::limit($campaign->description, 120) }}</p>

                                            <!-- Enhanced Progress Section -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-currency-exchange text-success me-1"></i>
                                                        <span class="fw-bold text-success">@currency($campaign->current_amount)</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-bullseye text-primary me-1"></i>
                                                        <span class="text-muted">@currency($campaign->target_amount)</span>
                                                    </div>
                                                </div>

                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $campaign->progress_percentage }}%"
                                                        aria-valuenow="{{ $campaign->progress_percentage }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        <span
                                                            class="fw-bold">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-people me-1"></i>
                                                        {{ $campaign->donations_count ?? 0 }} متبرع
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $campaign->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Enhanced Action Section -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('campaigns.show', $campaign) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-eye me-2"></i>
                                                    عرض التفاصيل
                                                </a>

                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <small class="text-muted d-block">المنشئ</small>
                                                        <small class="fw-bold">
                                                            <i class="bi bi-person-circle me-1"></i>
                                                            {{ $campaign->creator->name }}
                                                        </small>
                                                    </div>

                                                    @auth
                                                        <button class="btn btn-outline-danger btn-sm"
                                                            onclick="toggleFavorite({{ $campaign->id }})"
                                                            title="إضافة للمفضلة">
                                                            <i class="bi bi-heart"></i>
                                                        </button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($priorityCampaigns->count() > 1)
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#priorityCampaignsCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">السابق</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#priorityCampaignsCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">التالي</span>
                            </button>
                        @endif

                        <!-- Auto Play Indicators -->
                        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                            <div class="d-flex align-items-center bg-white bg-opacity-90 rounded-pill px-3 py-2 shadow-sm">
                                <div class="me-2">
                                    <div id="priorityCarouselProgress" class="bg-primary rounded-pill"
                                        style="width: 30px; height: 4px; transition: width 4s linear;"></div>
                                </div>
                                <small class="text-muted">
                                    <span id="currentSlideNumber">1</span> / {{ $priorityCampaigns->count() }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced JavaScript for Priority Campaigns Carousel -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const priorityCarousel = document.getElementById('priorityCampaignsCarousel');
                            const progressBar = document.getElementById('priorityCarouselProgress');
                            const slideNumber = document.getElementById('currentSlideNumber');

                            if (priorityCarousel && progressBar) {
                                let currentSlide = 0;
                                let isPlaying = true;
                                let progressInterval;

                                // Initialize Bootstrap carousel
                                const carousel = new bootstrap.Carousel(priorityCarousel, {
                                    interval: 4000,
                                    ride: 'carousel'
                                });

                                // Progress bar animation
                                function startProgress() {
                                    progressBar.style.width = '0%';
                                    progressBar.style.transition = 'width 4s linear';
                                    setTimeout(() => {
                                        if (isPlaying) {
                                            progressBar.style.width = '100%';
                                        }
                                    }, 100);
                                }

                                function resetProgress() {
                                    progressBar.style.transition = 'none';
                                    progressBar.style.width = '0%';
                                }

                                // Start initial progress
                                startProgress();

                                // Handle slide events
                                priorityCarousel.addEventListener('slide.bs.carousel', function(e) {
                                    resetProgress();
                                    currentSlide = e.to;
                                    slideNumber.textContent = currentSlide + 1;
                                });

                                priorityCarousel.addEventListener('slid.bs.carousel', function(e) {
                                    if (isPlaying) {
                                        startProgress();
                                    }
                                });

                                // Pause on hover
                                priorityCarousel.addEventListener('mouseenter', function() {
                                    carousel.pause();
                                    progressBar.style.animationPlayState = 'paused';
                                });

                                priorityCarousel.addEventListener('mouseleave', function() {
                                    if (isPlaying) {
                                        carousel.cycle();
                                        progressBar.style.animationPlayState = 'running';
                                    }
                                });

                                // Global functions for play/pause buttons
                                window.pauseCarousel = function() {
                                    isPlaying = false;
                                    carousel.pause();
                                    progressBar.style.animationPlayState = 'paused';
                                    document.getElementById('pauseIcon').style.opacity = '1';
                                    document.getElementById('playIcon').style.opacity = '0.5';
                                };

                                window.playCarousel = function() {
                                    isPlaying = true;
                                    carousel.cycle();
                                    startProgress();
                                    document.getElementById('pauseIcon').style.opacity = '0.5';
                                    document.getElementById('playIcon').style.opacity = '1';
                                };

                                // Touch/swipe support
                                let startX = 0;
                                let startY = 0;

                                priorityCarousel.addEventListener('touchstart', function(e) {
                                    startX = e.touches[0].clientX;
                                    startY = e.touches[0].clientY;
                                });

                                priorityCarousel.addEventListener('touchend', function(e) {
                                    if (!startX || !startY) return;

                                    let endX = e.changedTouches[0].clientX;
                                    let endY = e.changedTouches[0].clientY;

                                    let diffX = startX - endX;
                                    let diffY = startY - endY;

                                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                                        if (diffX > 0) {
                                            carousel.next();
                                        } else {
                                            carousel.prev();
                                        }
                                    }

                                    startX = 0;
                                    startY = 0;
                                });
                            }
                        });

                        // Favorite toggle function
                        @auth

                        function toggleFavorite(campaignId) {
                            fetch(`/api/campaigns/${campaignId}/favorite`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Authorization': 'Bearer {{ auth()->user()->createToken('web')->plainTextToken ?? '' }}',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update heart icon
                                        const heartIcon = event.target.closest('button').querySelector('i');
                                        if (data.is_favorite) {
                                            heartIcon.classList.remove('bi-heart');
                                            heartIcon.classList.add('bi-heart-fill');
                                            event.target.closest('button').classList.remove('btn-outline-danger');
                                            event.target.closest('button').classList.add('btn-danger');
                                        } else {
                                            heartIcon.classList.remove('bi-heart-fill');
                                            heartIcon.classList.add('bi-heart');
                                            event.target.closest('button').classList.remove('btn-danger');
                                            event.target.closest('button').classList.add('btn-outline-danger');
                                        }
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                        @endauth
                    </script>
                </div>
            </div>
        @endif

        <!-- All Campaigns -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>جميع الحملات</h2>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-outline-primary">
                        عرض المزيد
                        <i class="bi bi-arrow-left ms-2"></i>
                    </a>
                </div>

                @if ($campaigns->count() > 0)
                    <div class="row">
                        @foreach ($campaigns->take(6) as $campaign)
                            <div class="col-md-4 mb-4">
                                <div class="card campaign-card h-100 position-relative">
                                    <!-- Favorite Button -->
                                    @auth
                                        <button
                                            class="favorite-btn {{ $campaign->isFavoritedBy(auth()->id()) ? 'active' : '' }}"
                                            onclick="toggleFavorite({{ $campaign->id }}, this)">
                                            <i
                                                class="bi bi-heart{{ $campaign->isFavoritedBy(auth()->id()) ? '-fill' : '' }}"></i>
                                        </button>
                                    @endauth

                                    @if ($campaign->images && count($campaign->images) > 0)
                                        <img src="{{ asset('storage/' . $campaign->images[0]) }}" class="card-img-top"
                                            alt="{{ $campaign->title }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary d-flex align-items-center justify-content-center"
                                            style="height: 200px;">
                                            <i class="bi bi-heart-fill text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $campaign->title }}</h5>
                                        <p class="card-text flex-grow-1">{{ Str::limit($campaign->description, 100) }}</p>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>@currency($campaign->current_amount)</small>
                                                <small>@currency($campaign->target_amount)</small>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $campaign->progress_percentage }}%"
                                                    aria-valuenow="{{ $campaign->progress_percentage }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small
                                                class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%
                                                مكتمل</small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('campaigns.show', $campaign) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>
                                                عرض
                                            </a>
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $campaign->creator->name }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        لا توجد حملات متاحة حالياً
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-heart-fill text-primary display-4 mb-3"></i>
                        <h5 class="card-title">{{ number_format($stats['total_campaigns']) }}</h5>
                        <p class="card-text">حملة نشطة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-success display-4 mb-3"></i>
                        <h5 class="card-title">{{ number_format($stats['total_donors']) }}</h5>
                        <p class="card-text">متبرع</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-currency-dollar text-warning display-4 mb-3"></i>
                        <h5 class="card-title">{{ $stats['total_donations'] }}</h5>
                        <p class="card-text">إجمالي التبرعات</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill text-info display-4 mb-3"></i>
                        <h5 class="card-title">{{ number_format($stats['completed_campaigns']) }}</h5>
                        <p class="card-text">حملة مكتملة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How it works -->
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">كيف يعمل الموقع؟</h2>
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-person-plus display-6"></i>
                        </div>
                        <h5>1. إنشاء حساب</h5>
                        <p>قم بإنشاء حساب جديد بسهولة وأمان</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-wallet2 display-6"></i>
                        </div>
                        <h5>2. شحن المحفظة</h5>
                        <p>اشحن محفظتك بالمبلغ الذي تريد التبرع به</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-heart-fill display-6"></i>
                        </div>
                        <h5>3. تبرع</h5>
                        <p>اختر الحملة التي تريد دعمها وتبرع بسهولة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile App Download Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="text-white rounded-4 p-5 bg-gradient-mixed" id="app-download">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2 class="display-6 fw-bold mb-3">حمل التطبيق الآن</h2>
                            <p class="lead mb-4">استمتع بتجربة أفضل مع تطبيق نبض الحياة على هاتفك المحمول. تبرع بسهولة
                                وتابع حملاتك المفضلة في أي وقت ومكان.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#" class="btn btn-light btn-lg d-flex align-items-center">
                                    <i class="bi bi-apple me-2" style="font-size: 1.5rem;"></i>
                                    <div class="text-start">
                                        <small class="d-block">متوفر على</small>
                                        <strong>App Store</strong>
                                    </div>
                                </a>
                                <a href="#" class="btn btn-light btn-lg d-flex align-items-center">
                                    <i class="bi bi-google-play me-2" style="font-size: 1.5rem;"></i>
                                    <div class="text-start">
                                        <small class="d-block">متوفر على</small>
                                        <strong>Google Play</strong>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center">
                            <div class="position-relative">
                                <i class="bi bi-phone" style="font-size: 12rem; opacity: 0.3;"></i>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-5 animate-on-scroll">لماذا تختار نبض الحياة؟</h2>
                <div class="row g-4">
                    <div class="col-md-4 animate-on-scroll" data-delay="100">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-bounce"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-shield-check text-primary-custom" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">آمان وموثوقية</h5>
                                <p class="card-text text-muted">نضمن وصول تبرعاتك للمستحقين بأمان تام وشفافية كاملة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 animate-on-scroll" data-delay="200">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body text-center p-4">
                                <div class="bg-success-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-bounce"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-lightning-charge text-success-custom" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">سهولة الاستخدام</h5>
                                <p class="card-text text-muted">واجهة بسيطة وسهلة تمكنك من التبرع في خطوات قليلة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 animate-on-scroll" data-delay="300">
                        <div class="card h-100 border-0 shadow-sm feature-card">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 icon-bounce"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-graph-up text-primary-custom" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title">متابعة مستمرة</h5>
                                <p class="card-text text-muted">تابع تقدم الحملات وتأثير تبرعاتك بشكل مستمر</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-gradient-mixed text-white rounded-4 p-5">
                    <div class="row text-center">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <h3 class="h1 mb-2 counter-number" data-target="{{ $totalCampaigns }}">{{ $totalCampaigns }}
                            </h3>
                            <p class="mb-0">حملة نشطة</p>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <h3 class="h1 mb-2 counter-number" data-target="{{ $totalUsers }}">{{ $totalUsers }}
                            </h3>
                            <p class="mb-0">متبرع</p>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <h3 class="h1 mb-2 counter-number" data-target="{{ $totalDonations }}">{{ $totalDonations }}
                            </h3>
                            <p class="mb-0">تبرع</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="h1 mb-2 counter-number">@currency($totalAmount)</h3>
                            <p class="mb-0">إجمالي التبرعات</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-5 animate-on-scroll">كيف تعمل المنصة؟</h2>
                <div class="row g-4">
                    <div class="col-md-3 text-center animate-on-scroll" data-delay="100">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-icon mb-3">
                                <i class="bi bi-person-plus" style="font-size: 3rem; color: var(--primary-color);"></i>
                            </div>
                            <h5>إنشاء حساب</h5>
                            <p class="text-muted">سجل حسابك الجديد بسهولة</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center animate-on-scroll" data-delay="200">
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-icon mb-3">
                                <i class="bi bi-search" style="font-size: 3rem; color: var(--success-color);"></i>
                            </div>
                            <h5>اختر حملة</h5>
                            <p class="text-muted">تصفح الحملات واختر ما يناسبك</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center animate-on-scroll" data-delay="300">
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-icon mb-3">
                                <i class="bi bi-credit-card" style="font-size: 3rem; color: var(--primary-color);"></i>
                            </div>
                            <h5>تبرع بأمان</h5>
                            <p class="text-muted">ادفع بطريقة آمنة ومضمونة</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center animate-on-scroll" data-delay="400">
                        <div class="step-card">
                            <div class="step-number">4</div>
                            <div class="step-icon mb-3">
                                <i class="bi bi-heart-fill" style="font-size: 3rem; color: var(--success-color);"></i>
                            </div>
                            <h5>احدث فرقاً</h5>
                            <p class="text-muted">شاهد تأثير تبرعك الإيجابي</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="rounded-4 p-5 text-center" style="background-color: var(--light-color);">
                    <h3 class="mb-3 text-primary-custom">ابق على اطلاع</h3>
                    <p class="text-muted mb-4">اشترك في نشرتنا الإخبارية لتصلك آخر الحملات والأخبار</p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="أدخل بريدك الإلكتروني">
                                <button class="btn btn-primary" type="button">اشتراك</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleFavorite(campaignId, button) {
                // Disable button during request
                button.disabled = true;

                fetch(`/campaigns/${campaignId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const icon = button.querySelector('i');
                            if (data.is_favorited) {
                                icon.classList.remove('bi-heart');
                                icon.classList.add('bi-heart-fill');
                                button.classList.add('active');
                            } else {
                                icon.classList.remove('bi-heart-fill');
                                icon.classList.add('bi-heart');
                                button.classList.remove('active');
                            }

                            // Show success message
                            showAlert(data.message, 'success');
                        } else {
                            showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
            }

            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', alertHtml);

                // Auto dismiss after 3 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 3000);
            }

            // Scroll animations
            function animateOnScroll() {
                const elements = document.querySelectorAll('.animate-on-scroll');
                const windowHeight = window.innerHeight;

                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;

                    if (elementTop < windowHeight - elementVisible) {
                        const delay = element.getAttribute('data-delay') || 0;
                        setTimeout(() => {
                            element.classList.add('animated');
                        }, delay);
                    }
                });
            }

            // Counter animation
            function animateCounters() {
                const counters = document.querySelectorAll('.counter-number');

                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target')) || 0;
                    if (target === 0) return;

                    const increment = target / 100;
                    let current = 0;

                    const updateCounter = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current);
                            setTimeout(updateCounter, 20);
                        } else {
                            counter.textContent = target;
                        }
                    };

                    // Start animation when element is visible
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                updateCounter();
                                observer.unobserve(entry.target);
                            }
                        });
                    });

                    observer.observe(counter);
                });
            }

            // Navbar scroll effect
            function handleNavbarScroll() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }

            // Initialize animations
            document.addEventListener('DOMContentLoaded', function() {
                animateOnScroll();
                animateCounters();

                // Add scroll event listeners
                window.addEventListener('scroll', () => {
                    animateOnScroll();
                    handleNavbarScroll();
                });

                // Add hover effects to feature cards
                document.querySelectorAll('.feature-card').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-10px)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>
    @endpush
@endsection
