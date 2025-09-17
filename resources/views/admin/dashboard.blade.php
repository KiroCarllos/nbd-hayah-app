@extends('layouts.admin')

@section('title', 'لوحة التحكم - نبض الحياة')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>لوحة التحكم</h1>
                    <div>
                        <span class="badge bg-success">مدير</span>
                        <span class="text-muted">مرحباً، {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>{{ number_format($stats['total_users']) }}</h4>
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
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>{{ number_format($stats['total_campaigns']) }}</h4>
                                <p class="mb-0">إجمالي الحملات</p>
                                <small>({{ $stats['active_campaigns'] }} نشطة)</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>{{ number_format($stats['total_donations']) }}</h4>
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
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>@currency($stats['total_donated_amount'])</h4>
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

        <!-- Additional Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>رصيد المحافظ الإجمالي</h5>
                        <h3 class="text-success">@currency($stats['total_wallet_balance'])</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>التبرعات المعلقة</h5>
                        <h3 class="text-warning">{{ $stats['pending_donations'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>التبرعات الفاشلة</h5>
                        <h3 class="text-danger">{{ $stats['failed_donations'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Donations -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">آخر التبرعات</h5>
                    </div>
                    <div class="card-body">
                        @if ($recent_donations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>المتبرع</th>
                                            <th>الحملة</th>
                                            <th>المبلغ</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_donations as $donation)
                                            <tr>
                                                <td>
                                                    @if ($donation->is_anonymous)
                                                        <span class="text-muted">مجهول</span>
                                                    @else
                                                        {{ $donation->user->name }}
                                                    @endif
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}
                                                </td>
                                                <td><span class="badge bg-success">@currency($donation->amount)</span></td>
                                                <td><small>{{ $donation->created_at->diffForHumans() }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">لا توجد تبرعات حديثة</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المستخدمون الجدد</h5>
                    </div>
                    <div class="card-body">
                        @if ($recent_users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الهاتف</th>
                                            <th>رصيد المحفظة</th>
                                            <th>تاريخ التسجيل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_users as $user)
                                            <tr>
                                                <td>
                                                    @if ($user->profile_image)
                                                        <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                            alt="Profile" class="rounded-circle me-1" width="20"
                                                            height="20">
                                                    @endif
                                                    {{ $user->name }}
                                                </td>
                                                <td>{{ $user->mobile }}</td>
                                                <td>@currency($user->wallet_balance)</td>
                                                <td><small>{{ $user->created_at->diffForHumans() }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">لا توجد مستخدمين جدد</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Campaigns and Donors -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">أفضل الحملات (حسب عدد التبرعات)</h5>
                    </div>
                    <div class="card-body">
                        @if ($top_campaigns->count() > 0)
                            @foreach ($top_campaigns as $campaign)
                                <div
                                    class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div>
                                        <strong>{{ \Illuminate\Support\Str::limit($campaign->title, 40) }}</strong>
                                        <br>
                                        <small class="text-muted">بواسطة: {{ $campaign->creator->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $campaign->donations_count }} تبرع</span>
                                        <br>
                                        <small class="text-success">@currency($campaign->current_amount)</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">لا توجد حملات</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">أفضل المتبرعين</h5>
                    </div>
                    <div class="card-body">
                        @if ($top_donors->count() > 0)
                            @foreach ($top_donors as $donor)
                                <div
                                    class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-center">
                                        @if ($donor->profile_image)
                                            <img src="{{ asset('storage/' . $donor->profile_image) }}" alt="Profile"
                                                class="rounded-circle me-2" width="30" height="30">
                                        @else
                                            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                                        @endif
                                        <div>
                                            <strong>{{ $donor->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $donor->mobile }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success">@currency($donor->total_donated)</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">لا توجد تبرعات</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
