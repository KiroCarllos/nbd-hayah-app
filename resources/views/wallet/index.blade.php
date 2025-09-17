@extends('layouts.app')

@section('title', 'المحفظة - نبض الحياة')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تاريخ المعاملات</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        @if($transaction->type === 'credit')
                                            <span class="badge bg-success">إيداع</span>
                                        @else
                                            <span class="badge bg-danger">سحب</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->description }}</td>
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
                    
                    <!-- Pagination -->
                    {{ $transactions->links() }}
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-wallet2 text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">لا توجد معاملات بعد</p>
                        <a href="{{ route('wallet.charge') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            شحن المحفظة
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Wallet Balance Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">{{ number_format($user->wallet_balance, 2) }} ر.س</h3>
                    <p class="text-muted">رصيد المحفظة</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('wallet.charge') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            شحن المحفظة
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-success">
                            <i class="bi bi-heart me-2"></i>
                            تصفح الحملات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">إحصائيات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h6 class="mb-0">{{ $transactions->where('type', 'credit')->where('status', 'completed')->count() }}</h6>
                            <small class="text-muted">عمليات شحن</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ $transactions->where('type', 'debit')->where('status', 'completed')->count() }}</h6>
                            <small class="text-muted">تبرعات</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="mb-0">{{ number_format($transactions->where('type', 'credit')->where('status', 'completed')->sum('amount'), 2) }} ر.س</h6>
                        <small class="text-muted">إجمالي الشحن</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
