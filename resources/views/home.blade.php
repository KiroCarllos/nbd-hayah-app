@extends('layouts.app')

@section('title', 'الرئيسية - نبض الحياة')

@section('content')
    <div class="container">
        <!-- Hero Section -->
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
                                                            <span>تم جمع: {{ number_format($campaign->current_amount, 2) }}
                                                                ر.س</span>
                                                            <span>الهدف: {{ number_format($campaign->target_amount, 2) }}
                                                                ر.س</span>
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
                                        <button class="favorite-btn" onclick="toggleFavorite({{ $campaign->id }}, this)">
                                            <i class="bi bi-heart"></i>
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
                                                <small>{{ number_format($campaign->current_amount, 0) }} ر.س</small>
                                                <small>{{ number_format($campaign->target_amount, 0) }} ر.س</small>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $campaign->progress_percentage }}%"
                                                    aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">
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
                        <h5 class="card-title">{{ number_format($stats['total_donations'], 0) }} ر.س</h5>
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
    </div>

    @push('scripts')
        <script>
            function toggleFavorite(campaignId, button) {
                fetch(`/campaigns/${campaignId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const icon = button.querySelector('i');
                        if (data.is_favorite) {
                            icon.classList.remove('bi-heart');
                            icon.classList.add('bi-heart-fill');
                            button.classList.add('active');
                        } else {
                            icon.classList.remove('bi-heart-fill');
                            icon.classList.add('bi-heart');
                            button.classList.remove('active');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                    });
            }
        </script>
    @endpush
@endsection
