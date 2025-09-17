@extends('layouts.admin')

@section('title', 'المعاملات المالية - لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>المعاملات المالية</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $transactions->total() }}</h4>
                        <p class="mb-0">إجمالي المعاملات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt" style="font-size: 2rem;"></i>
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
                        <h4>{{ number_format($transactions->where('type', 'charge')->sum('amount'), 0) }} ر.س</h4>
                        <p class="mb-0">إجمالي الشحن</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-plus-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ number_format($transactions->where('type', 'donation')->sum('amount'), 0) }} ر.س</h4>
                        <p class="mb-0">إجمالي التبرعات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-dash-circle" style="font-size: 2rem;"></i>
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
                        <h4>{{ number_format($transactions->avg('amount'), 0) }} ر.س</h4>
                        <p class="mb-0">متوسط المعاملة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-bar-chart" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">قائمة المعاملات المالية</h5>
    </div>
    <div class="card-body">
        @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>نوع المعاملة</th>
                        <th>المبلغ</th>
                        <th>الرصيد السابق</th>
                        <th>الرصيد الجديد</th>
                        <th>الوصف</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($transaction->user->profile_image)
                                    <img src="{{ asset('storage/' . $transaction->user->profile_image) }}" alt="Profile" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $transaction->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $transaction->user->mobile }}</small>
                                </div>
                            </div>
                        </td>
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
                            @if($transaction->description)
                                {{ \Illuminate\Support\Str::limit($transaction->description, 50) }}
                            @else
                                <span class="text-muted">لا يوجد وصف</span>
                            @endif
                        </td>
                        <td>
                            {{ $transaction->created_at->format('Y/m/d H:i') }}
                            <br>
                            <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $transaction->user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-person"></i>
                                </a>
                                @if($transaction->type === 'donation' && $transaction->description)
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="{{ $transaction->description }}">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">لا توجد معاملات مالية</h4>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
@endpush
