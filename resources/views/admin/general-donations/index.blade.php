@extends('layouts.admin')

@section('title', 'التبرعات العامة السريعة')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-lightning-fill text-warning me-2"></i>
                    التبرعات العامة السريعة
                </h1>
                <p class="text-muted">إدارة ومراقبة التبرعات العامة السريعة</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>{{ $stats['total_donations'] }}</h4>
                                <p class="mb-0">إجمالي التبرعات</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-lightning-fill" style="font-size: 2rem;"></i>
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
                                <h4>{{ number_format($stats['total_amount'], 2) }}</h4>
                                <p class="mb-0">إجمالي المبلغ (ج.م)</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-exchange" style="font-size: 2rem;"></i>
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
                                <h4>{{ $stats['completed_donations'] }}</h4>
                                <p class="mb-0">تبرعات مكتملة</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>{{ $stats['anonymous_donations'] }}</h4>
                                <p class="mb-0">تبرعات مجهولة</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-eye-slash-fill" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">البحث</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="اسم المتبرع أو الرسالة...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">الحالة</label>
                        <select class="form-select" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فاشل</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">النوع</label>
                        <select class="form-select" name="anonymous">
                            <option value="">جميع الأنواع</option>
                            <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>علني</option>
                            <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>مجهول</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>بحث
                            </button>
                            <a href="{{ route('admin.general-donations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Donations Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">قائمة التبرعات العامة ({{ $donations->total() }})</h5>
            </div>
            <div class="card-body">
                @if ($donations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المتبرع</th>
                                    <th>المبلغ</th>
                                    <th>النوع</th>
                                    <th>الرسالة</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donations as $donation)
                                    <tr>
                                        <td>
                                            @if ($donation->is_anonymous)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('secret.jpg') }}" alt="متبرع مجهول"
                                                        class="rounded-circle me-3" width="32" height="32" style="object-fit: cover;">
                                                    <span class="text-muted">متبرع مجهول</span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    @if ($donation->user->profile_image && $donation->user->profile_image !== 'default.png')
                                                        <img src="{{ asset('storage/' . $donation->user->profile_image) }}"
                                                            alt="Profile" class="rounded-circle me-3" width="32"
                                                            height="32" style="object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('default.png') }}" alt="صورة افتراضية"
                                                            class="rounded-circle me-3" width="32" height="32" style="object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $donation->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $donation->user->mobile }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark fs-6">{{ number_format($donation->amount, 2) }} ج.م</span>
                                        </td>
                                        <td>
                                            @if ($donation->is_anonymous)
                                                <span class="badge bg-secondary">مجهول</span>
                                            @else
                                                <span class="badge bg-info">علني</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($donation->message)
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                    title="{{ $donation->message }}">
                                                    {{ $donation->message }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $donation->created_at->format('Y/m/d H:i') }}
                                            <br>
                                            <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if ($donation->status == 'completed')
                                                <span class="badge bg-success">مكتمل</span>
                                            @elseif ($donation->status == 'pending')
                                                <span class="badge bg-warning">معلق</span>
                                            @else
                                                <span class="badge bg-danger">فاشل</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.general-donations.show', $donation) }}" 
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i>عرض
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $donations->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-lightning text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">لا توجد تبرعات عامة</h4>
                        <p class="text-muted">لم يتم العثور على أي تبرعات عامة سريعة بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
