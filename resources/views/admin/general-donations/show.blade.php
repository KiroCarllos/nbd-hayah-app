@extends('layouts.admin')

@section('title', 'تفاصيل التبرع العام')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>
                    تفاصيل التبرع العام #{{ $donation->id }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.general-donations.index') }}">التبرعات العامة</a>
                        </li>
                        <li class="breadcrumb-item active">تفاصيل التبرع #{{ $donation->id }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.general-donations.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>
        </div>

        <div class="row">
            <!-- معلومات التبرع -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-info-circle me-2"></i>معلومات التبرع
                        </h6>
                        <span
                            class="badge badge-{{ $donation->status === 'completed' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $donation->status === 'completed' ? 'مكتمل' : ($donation->status === 'pending' ? 'قيد المعالجة' : 'فاشل') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">رقم التبرع:</label>
                                    <p class="mb-0">#{{ $donation->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">المبلغ:</label>
                                    <p class="mb-0 text-success fw-bold fs-5">@currency($donation->amount)</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">طريقة الدفع:</label>
                                    <p class="mb-0">
                                        <i class="bi bi-wallet2 me-1"></i>
                                        {{ $donation->payment_method === 'wallet' ? 'المحفظة' : $donation->payment_method }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">حالة التبرع:</label>
                                    <p class="mb-0">
                                        @if ($donation->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>مكتمل
                                            </span>
                                        @elseif($donation->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>قيد المعالجة
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>فاشل
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">تاريخ التبرع:</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $donation->created_at->format('Y-m-d H:i:s') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">نوع التبرع:</label>
                                    <p class="mb-0">
                                        @if ($donation->is_anonymous)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-eye-slash me-1"></i>مجهول
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="bi bi-person me-1"></i>علني
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if ($donation->transaction_id)
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">رقم المعاملة:</label>
                                        <p class="mb-0 font-monospace">{{ $donation->transaction_id }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($donation->message)
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">رسالة التبرع:</label>
                                        <div class="alert alert-light">
                                            <i class="bi bi-chat-quote me-2"></i>
                                            {{ $donation->message }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات المتبرع -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-person me-2"></i>معلومات المتبرع
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($donation->is_anonymous)
                            <div class="mb-3">
                                <img src="{{ asset('secret.jpg') }}" alt="متبرع مجهول" class="rounded-circle mb-3"
                                    width="80" height="80" style="object-fit: cover;">
                                <h5 class="text-muted">متبرع مجهول</h5>
                                <p class="text-muted small">اختار المتبرع عدم الكشف عن هويته</p>
                            </div>
                        @else
                            <div class="mb-3">
                                @if ($donation->user->profile_image)
                                    <img src="{{ asset('storage/' . $donation->user->profile_image) }}"
                                        alt="{{ $donation->user->name }}" class="rounded-circle mb-3" width="80"
                                        height="80" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('public/default.png') }}" alt="{{ $donation->user->name }}"
                                        class="rounded-circle mb-3" width="80" height="80"
                                        style="object-fit: cover;">
                                @endif
                                <h5>{{ $donation->user->name }}</h5>
                                <p class="text-muted">{{ $donation->user->mobile }}</p>
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-primary">{{ $donation->user->campaign_donations_count ?? 0 }}</h6>
                                        <small class="text-muted">تبرعات للحملات</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-warning">{{ $donation->user->general_donations_count ?? 0 }}</h6>
                                    <small class="text-muted">تبرعات عامة</small>
                                </div>
                            </div>

                            @if ($donation->user)
                                <div class="mt-3">
                                    <a href="{{ route('admin.users.show', $donation->user->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>عرض الملف الشخصي
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- إحصائيات سريعة -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-graph-up me-2"></i>إحصائيات سريعة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>إجمالي التبرعات العامة اليوم:</span>
                                <span class="fw-bold text-success">@currency($todayTotal ?? 0)</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>عدد التبرعات اليوم:</span>
                                <span class="fw-bold text-primary">{{ $todayCount ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>متوسط التبرع:</span>
                                <span class="fw-bold text-info">@currency($averageDonation ?? 0)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // يمكن إضافة JavaScript إضافي هنا إذا لزم الأمر
    </script>
@endsection
