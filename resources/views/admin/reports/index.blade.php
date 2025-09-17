@extends('layouts.admin')

@section('title', 'التقارير - لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>التقارير والإحصائيات</h1>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\User::count() }}</h4>
                        <p class="mb-0">إجمالي المستخدمين</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Campaign::count() }}</h4>
                        <p class="mb-0">إجمالي الحملات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Donation::count() }}</h4>
                        <p class="mb-0">إجمالي التبرعات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-gift" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ number_format(\App\Models\Donation::sum('amount'), 0) }} ر.س</h4>
                        <p class="mb-0">إجمالي المبلغ المتبرع</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Top Campaigns -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">أفضل الحملات (حسب المبلغ المحصل)</h5>
            </div>
            <div class="card-body">
                @php
                    $topCampaigns = \App\Models\Campaign::orderBy('current_amount', 'desc')->take(5)->get();
                @endphp
                
                @if($topCampaigns->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($topCampaigns as $index => $campaign)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-3">{{ $index + 1 }}</span>
                            <div>
                                <h6 class="mb-1">{{ \Illuminate\Support\Str::limit($campaign->title, 40) }}</h6>
                                <small class="text-muted">{{ $campaign->creator->name }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success fs-6">{{ number_format($campaign->current_amount, 0) }} ر.س</span>
                            <br>
                            <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center">لا توجد حملات</p>
                @endif
            </div>
        </div>

        <!-- Top Donors -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">أكثر المتبرعين نشاطاً</h5>
            </div>
            <div class="card-body">
                @php
                    $topDonors = \App\Models\User::withSum('donations', 'amount')
                        ->withCount('donations')
                        ->having('donations_sum_amount', '>', 0)
                        ->orderBy('donations_sum_amount', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($topDonors->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($topDonors as $index => $donor)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning rounded-pill me-3">{{ $index + 1 }}</span>
                            <div class="d-flex align-items-center">
                                @if($donor->profile_image)
                                    <img src="{{ asset('storage/' . $donor->profile_image) }}" alt="Profile" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-2" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $donor->name }}</h6>
                                    <small class="text-muted">{{ $donor->donations_count }} تبرع</small>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-success fs-6">{{ number_format($donor->donations_sum_amount, 0) }} ر.س</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center">لا يوجد متبرعين</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Recent Activity -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">النشاط الأخير</h5>
            </div>
            <div class="card-body">
                @php
                    $recentDonations = \App\Models\Donation::with(['user', 'campaign'])
                        ->latest()
                        ->take(8)
                        ->get();
                @endphp
                
                @if($recentDonations->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentDonations as $donation)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                @if($donation->is_anonymous)
                                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 32px; height: 32px;">
                                        <i class="bi bi-eye-slash text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">متبرع مجهول</h6>
                                        <small class="text-muted">تبرع لحملة: {{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}</small>
                                    </div>
                                @else
                                    @if($donation->user->profile_image)
                                        <img src="{{ asset('storage/' . $donation->user->profile_image) }}" alt="Profile" class="rounded-circle me-3" width="32" height="32" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ $donation->user->name }}</h6>
                                        <small class="text-muted">تبرع لحملة: {{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">{{ number_format($donation->amount, 0) }} ر.س</span>
                                <br>
                                <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center">لا يوجد نشاط حديث</p>
                @endif
            </div>
        </div>

        <!-- Monthly Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إحصائيات الشهر الحالي</h5>
            </div>
            <div class="card-body">
                @php
                    $currentMonth = now()->startOfMonth();
                    $monthlyUsers = \App\Models\User::where('created_at', '>=', $currentMonth)->count();
                    $monthlyCampaigns = \App\Models\Campaign::where('created_at', '>=', $currentMonth)->count();
                    $monthlyDonations = \App\Models\Donation::where('created_at', '>=', $currentMonth)->count();
                    $monthlyAmount = \App\Models\Donation::where('created_at', '>=', $currentMonth)->sum('amount');
                @endphp
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $monthlyUsers }}</h4>
                        <small class="text-muted">مستخدم جديد</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success">{{ $monthlyCampaigns }}</h4>
                        <small class="text-muted">حملة جديدة</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $monthlyDonations }}</h4>
                        <small class="text-muted">تبرع</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ number_format($monthlyAmount, 0) }} ر.س</h4>
                        <small class="text-muted">مبلغ التبرعات</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
