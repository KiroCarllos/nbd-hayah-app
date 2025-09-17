@extends('layouts.app')

@section('title', 'الرئيسية - نبض الحياة')

@section('content')
    <!-- Main Slider -->
    @if ($sliders->count() > 0)
        <div id="mainSlider" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
            <style>
                .carousel-item {
                    transition: transform 0.8s ease-in-out;
                }

                .carousel-caption {
                    bottom: 20%;
                }

                .carousel-control-prev,
                .carousel-control-next {
                    width: 5%;
                }

                .carousel-indicators button {
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    margin: 0 5px;
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
                        <div class="position-relative">
                            @if (file_exists(public_path('storage/' . $slider->image)))
                                <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100"
                                    alt="{{ $slider->title }}" style="height: 500px; object-fit: cover;">
                            @else
                                <!-- Fallback gradient background -->
                                <div class="d-block w-100"
                                    style="height: 500px; background: linear-gradient(135deg,
                                    @if ($index % 3 == 0) #ff4d57 0%, #31a354 100%
                                    @elseif($index % 3 == 1) #31a354 0%, #ff4d57 100%
                                    @else #ff4d57 0%, #e6e6e6 100% @endif
                                    ); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-heart-fill text-white" style="font-size: 8rem; opacity: 0.3;"></i>
                                </div>
                            @endif
                            <div class="carousel-caption d-none d-md-block">
                                <div class="bg-dark bg-opacity-50 p-4 rounded">
                                    <h2 class="display-5 fw-bold">{{ $slider->title }}</h2>
                                    @if ($slider->description)
                                        <p class="lead">{{ $slider->description }}</p>
                                    @endif
                                    @if ($slider->button_text && $slider->button_link)
                                        <a href="{{ $slider->button_link }}" class="btn btn-primary btn-lg">
                                            {{ $slider->button_text }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
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

        <!-- Priority Campaigns Slider -->
        @if ($priorityCampaigns->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="mb-4">الحملات المميزة</h2>
                    <div id="priorityCampaignsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($priorityCampaigns as $index => $campaign)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="card">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                @if ($campaign->images && count($campaign->images) > 0)
                                                    <img src="{{ asset('storage/' . $campaign->images[0]) }}"
                                                        class="img-fluid rounded-start h-100" alt="{{ $campaign->title }}"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div
                                                        class="bg-primary d-flex align-items-center justify-content-center h-100 rounded-start">
                                                        <i class="bi bi-heart-fill text-white" style="font-size: 4rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $campaign->title }}</h5>
                                                    <p class="card-text">{{ Str::limit($campaign->description, 150) }}</p>

                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>تم جمع: @currency($campaign->current_amount)</span>
                                                            <span>الهدف: @currency($campaign->target_amount)</span>
                                                        </div>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $campaign->progress_percentage }}%"
                                                                aria-valuenow="{{ $campaign->progress_percentage }}"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                {{ number_format($campaign->progress_percentage, 1) }}%
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a href="{{ route('campaigns.show', $campaign) }}"
                                                            class="btn btn-primary">
                                                            <i class="bi bi-eye me-2"></i>
                                                            عرض التفاصيل
                                                        </a>
                                                        <small class="text-muted">
                                                            <i class="bi bi-person me-1"></i>
                                                            {{ $campaign->creator->name }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($priorityCampaigns->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#priorityCampaignsCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">السابق</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#priorityCampaignsCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">التالي</span>
                            </button>
                        @endif
                    </div>
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
                        <h5 class="card-title">@currency($stats['total_donations'])</h5>
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
