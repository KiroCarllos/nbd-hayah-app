@extends('layouts.admin')

@section('title', 'تعديل المستخدم - لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>تعديل المستخدم: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right me-2"></i>
        العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل بيانات المستخدم</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mobile -->
                    <div class="mb-3">
                        <label for="mobile" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Wallet Balance -->
                    <div class="mb-3">
                        <label for="wallet_balance" class="form-label">رصيد المحفظة (ر.س)</label>
                        <input type="number" class="form-control @error('wallet_balance') is-invalid @enderror" id="wallet_balance" name="wallet_balance" value="{{ old('wallet_balance', $user->wallet_balance) }}" min="0" step="0.01">
                        @error('wallet_balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">يمكنك تعديل رصيد المحفظة مباشرة</div>
                    </div>

                    <!-- Admin Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_admin') is-invalid @enderror" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_admin">
                                مدير النظام
                            </label>
                            @error('is_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">منح صلاحيات الإدارة للمستخدم</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Profile Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">معاينة الملف الشخصي</h6>
            </div>
            <div class="card-body text-center">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                @else
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-person text-white" style="font-size: 2.5rem;"></i>
                    </div>
                @endif
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted">{{ $user->mobile }}</p>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">إحصائيات المستخدم</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success">{{ number_format($user->wallet_balance, 2) }}</h4>
                        <small class="text-muted">ر.س رصيد المحفظة</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->donations()->count() }}</h4>
                        <small class="text-muted">تبرع</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ number_format($user->donations()->sum('amount'), 2) }}</h4>
                        <small class="text-muted">ر.س إجمالي التبرعات</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $user->favorites()->count() }}</h4>
                        <small class="text-muted">حملة مفضلة</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">معلومات الحساب</h6>
            </div>
            <div class="card-body">
                <p><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y/m/d') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y/m/d H:i') }}</p>
                <p><strong>ID:</strong> {{ $user->id }}</p>
                <p><strong>نوع الحساب الحالي:</strong> 
                    @if($user->is_admin)
                        <span class="badge bg-danger">مدير</span>
                    @else
                        <span class="badge bg-secondary">مستخدم عادي</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Warning Card -->
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    تحذير
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        تعديل رصيد المحفظة سيؤثر على الرصيد الفعلي للمستخدم
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        منح صلاحيات الإدارة يعطي المستخدم وصولاً كاملاً للنظام
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        تأكد من صحة البيانات قبل الحفظ
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
