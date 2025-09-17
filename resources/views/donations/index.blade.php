@extends('layouts.app')

@section('title', 'تبرعاتي - نبض الحياة')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>تبرعاتي</h1>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-heart me-2"></i>
                        تصفح الحملات
                    </a>
                </div>
            </div>
        </div>

        @if ($donations->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>الحملة</th>
                                            <th>المبلغ</th>
                                            <th>النوع</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($donations as $donation)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $donation->created_at->format('Y/m/d') }}</strong>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $donation->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $donation->campaign->title }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-person me-1"></i>
                                                            {{ $donation->campaign->creator->name }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success fs-6">
                                                        @currency($donation->amount)
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($donation->is_anonymous)
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-eye-slash me-1"></i>
                                                            مجهول
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info">
                                                            <i class="bi bi-eye me-1"></i>
                                                            علني
                                                        </span>
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
                                                <td>
                                                    <a href="{{ route('campaigns.show', $donation->campaign) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>
                                                        عرض الحملة
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $donations->links() }}
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $donations->count() }}</h4>
                            <p class="mb-0">إجمالي التبرعات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">@currency($donations->sum('amount'))</h4>
                            <p class="mb-0">إجمالي المبلغ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar" style="font-size: 2rem;"></i>
                            <h4 class="mt-2">{{ $donations->first()?->created_at?->diffForHumans() ?? 'لا يوجد' }}</h4>
                            <p class="mb-0">آخر تبرع</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">لم تقم بأي تبرعات بعد</h4>
                            <p class="text-muted">ابدأ رحلتك الخيرية وساهم في إحداث فرق في المجتمع</p>
                            <div class="mt-4">
                                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-heart-fill me-2"></i>
                                    تصفح الحملات
                                </a>
                                <a href="{{ route('wallet.charge') }}" class="btn btn-outline-success btn-lg ms-2">
                                    <i class="bi bi-wallet2 me-2"></i>
                                    شحن المحفظة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
