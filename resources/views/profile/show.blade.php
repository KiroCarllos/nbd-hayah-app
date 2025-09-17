@extends('layouts.app')

@section('title', 'الملف الشخصي - نبض الحياة')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                    @else
                        <i class="bi bi-person-circle text-muted mb-3" style="font-size: 7rem;"></i>
                    @endif
                    
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted">{{ $user->mobile }}</p>
                    
                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>
                            تعديل الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wallet Balance -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <i class="bi bi-wallet2 text-success" style="font-size: 3rem;"></i>
                    <h3 class="mt-2 text-success">{{ number_format($user->wallet_balance, 2) }} ر.س</h3>
                    <p class="text-muted">رصيد المحفظة</p>
                    <a href="{{ route('wallet.charge') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>
                        شحن المحفظة
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['total_donations'] }}</h4>
                                    <p class="mb-0">إجمالي التبرعات</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['total_donated_amount'], 2) }} ر.س</h4>
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

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['total_wallet_transactions'] }}</h4>
                                    <p class="mb-0">معاملات المحفظة</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['total_charged_amount'], 2) }} ر.س</h4>
                                    <p class="mb-0">إجمالي الشحن</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-credit-card" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">آخر المعاملات</h5>
                    <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($recent_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النوع</th>
                                    <th>الوصف</th>
                                    <th>المبلغ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_transactions as $transaction)
                                <tr>
                                    <td><small>{{ $transaction->created_at->format('Y/m/d H:i') }}</small></td>
                                    <td>
                                        @if($transaction->type === 'credit')
                                            <span class="badge bg-success">إيداع</span>
                                        @else
                                            <span class="badge bg-danger">سحب</span>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($transaction->description, 30) }}</td>
                                    <td>
                                        @if($transaction->type === 'credit')
                                            <span class="text-success">+{{ number_format($transaction->amount, 2) }} ر.س</span>
                                        @else
                                            <span class="text-danger">-{{ number_format($transaction->amount, 2) }} ر.س</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($transaction->status)
                                            @case('completed')
                                                <span class="badge bg-success">مكتملة</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">معلقة</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">فاشلة</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">لا توجد معاملات</p>
                    @endif
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">آخر التبرعات</h5>
                    <a href="{{ route('donations.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                </div>
                <div class="card-body">
                    @if($recent_donations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الحملة</th>
                                    <th>المبلغ</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_donations as $donation)
                                <tr>
                                    <td><small>{{ $donation->created_at->format('Y/m/d H:i') }}</small></td>
                                    <td>
                                        <a href="{{ route('campaigns.show', $donation->campaign) }}" class="text-decoration-none">
                                            {{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}
                                        </a>
                                    </td>
                                    <td><span class="badge bg-success">{{ number_format($donation->amount, 2) }} ر.س</span></td>
                                    <td>
                                        @if($donation->is_anonymous)
                                            <span class="badge bg-secondary">مجهول</span>
                                        @else
                                            <span class="badge bg-info">علني</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($donation->status)
                                            @case('completed')
                                                <span class="badge bg-success">مكتمل</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">معلق</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">فاشل</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">لم تقم بأي تبرعات بعد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
