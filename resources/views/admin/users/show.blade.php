@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم - لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $user->name }}</h1>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil me-2"></i>
            تعديل
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- User Profile -->
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                @else
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width: 120px; height: 120px;">
                        <i class="bi bi-person text-white" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted">{{ $user->mobile }}</p>
                
                @if($user->is_admin)
                    <span class="badge bg-danger fs-6">مدير</span>
                @else
                    <span class="badge bg-secondary fs-6">مستخدم</span>
                @endif
            </div>
        </div>

        <!-- User Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">الإحصائيات</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success">{{ number_format($user->wallet_balance, 2) }}</h4>
                        <small class="text-muted">ر.س رصيد المحفظة</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->donations->count() }}</h4>
                        <small class="text-muted">تبرع</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ number_format($user->donations->sum('amount'), 2) }}</h4>
                        <small class="text-muted">ر.س إجمالي التبرعات</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $user->favorites->count() }}</h4>
                        <small class="text-muted">حملة مفضلة</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">معلومات الحساب</h5>
            </div>
            <div class="card-body">
                <p><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y/m/d H:i') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y/m/d H:i') }}</p>
                <p><strong>ID:</strong> {{ $user->id }}</p>
                <p><strong>نوع الحساب:</strong> 
                    @if($user->is_admin)
                        <span class="badge bg-danger">مدير</span>
                    @else
                        <span class="badge bg-secondary">مستخدم عادي</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Recent Donations -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">آخر التبرعات ({{ $user->donations->count() }})</h5>
            </div>
            <div class="card-body">
                @if($user->donations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الحملة</th>
                                <th>المبلغ</th>
                                <th>النوع</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->donations->take(10) as $donation)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($donation->campaign->images && count($donation->campaign->images) > 0)
                                            <img src="{{ asset('storage/' . $donation->campaign->images[0]) }}" alt="Campaign" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="bg-primary d-flex align-items-center justify-content-center rounded me-3" style="width: 40px; height: 40px;">
                                                <i class="bi bi-heart-fill text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $donation->campaign->creator->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">{{ number_format($donation->amount, 2) }} ر.س</span>
                                </td>
                                <td>
                                    @if($donation->is_anonymous)
                                        <span class="badge bg-secondary">مجهول</span>
                                    @else
                                        <span class="badge bg-primary">علني</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $donation->created_at->format('Y/m/d H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.campaigns.show', $donation->campaign) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-gift text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد تبرعات</h5>
                </div>
                @endif
            </div>
        </div>

        <!-- Wallet Transactions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">آخر المعاملات المالية ({{ $user->walletTransactions->count() }})</h5>
            </div>
            <div class="card-body">
                @if($user->walletTransactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>نوع المعاملة</th>
                                <th>المبلغ</th>
                                <th>الرصيد السابق</th>
                                <th>الرصيد الجديد</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->walletTransactions->take(10) as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->type === 'charge')
                                        <span class="badge bg-success">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            شحن محفظة
                                        </span>
                                    @elseif($transaction->type === 'donation')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-heart-fill me-1"></i>
                                            تبرع
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $transaction->type }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->type === 'charge')
                                        <span class="text-success fw-bold">+{{ number_format($transaction->amount, 2) }} ر.س</span>
                                    @else
                                        <span class="text-danger fw-bold">-{{ number_format($transaction->amount, 2) }} ر.س</span>
                                    @endif
                                </td>
                                <td>{{ number_format($transaction->previous_balance, 2) }} ر.س</td>
                                <td>{{ number_format($transaction->new_balance, 2) }} ر.س</td>
                                <td>
                                    {{ $transaction->created_at->format('Y/m/d H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد معاملات مالية</h5>
                </div>
                @endif
            </div>
        </div>

        <!-- Favorite Campaigns -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">الحملات المفضلة ({{ $user->favorites->count() }})</h5>
            </div>
            <div class="card-body">
                @if($user->favorites->count() > 0)
                <div class="row">
                    @foreach($user->favorites->take(6) as $favorite)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4">
                                    @if($favorite->campaign->images && count($favorite->campaign->images) > 0)
                                        <img src="{{ asset('storage/' . $favorite->campaign->images[0]) }}" class="img-fluid rounded-start h-100" alt="{{ $favorite->campaign->title }}" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary d-flex align-items-center justify-content-center rounded-start h-100">
                                            <i class="bi bi-heart-fill text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-2">
                                        <h6 class="card-title">{{ \Illuminate\Support\Str::limit($favorite->campaign->title, 30) }}</h6>
                                        <small class="text-muted">{{ number_format($favorite->campaign->progress_percentage, 1) }}% مكتمل</small>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" style="width: {{ $favorite->campaign->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد حملات مفضلة</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
