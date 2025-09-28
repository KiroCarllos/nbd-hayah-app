@extends('layouts.app')

@section('title', 'إنشاء حساب - نبض الحياة')

@section('content')
    <div class="min-vh-100 d-flex align-items-center py-5 bg-gradient-success">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">
                            <!-- Logo and Title -->
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <img src="{{ asset('logo-text-bottom.png') }}" alt="نبض الحياة" class="logo-large">
                                </div>
                                <h2 class="fw-bold text-dark">انضم إلى نبض الحياة</h2>
                                <p class="text-muted">ابدأ رحلتك في العطاء وإحداث الفرق</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="name" class="form-label fw-semibold">الاسم الكامل <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-person text-muted"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                                    id="name" name="name" value="{{ old('name') }}"
                                                    placeholder="أدخل اسمك الكامل" required autofocus>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="email" class="form-label fw-semibold">البريد الإلكتروني
                                                <small class="text-muted">(اختياري)</small></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-envelope text-muted"></i>
                                                </span>
                                                <input type="email"
                                                    class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                                    id="email" name="email" value="{{ old('email') }}"
                                                    placeholder="example@email.com (اختياري)">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">يمكنك إضافة البريد الإلكتروني لاحقاً من الملف الشخصي
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mobile" class="form-label">رقم الموبايل <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                                            @error('mobile')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_image" class="form-label">الصورة الشخصية
                                                <span class="text-muted">(اختياري)</span></label>
                                            <input type="file"
                                                class="form-control @error('profile_image') is-invalid @enderror"
                                                id="profile_image" name="profile_image" accept="image/*">
                                            @error('profile_image')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="bi bi-info-circle me-1"></i>
                                                الحد الأقصى: 2 ميجابايت. الأنواع المدعومة: JPG, PNG, GIF<br>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">كلمة المرور <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="form-text">يجب أن تكون 8 أحرف على الأقل</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            أوافق على <a href="{{ route('terms') }}" target="_blank">الشروط والأحكام</a>
                                            و
                                            <a href="{{ route('privacy') }}" target="_blank">سياسة الخصوصية</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" id="registerBtn">
                                        <span class="btn-text">
                                            إنشاء الحساب
                                            <i class="bi bi-person-plus me-2"></i>
                                        </span>
                                        <span class="btn-loading d-none">
                                            جاري إنشاء الحساب...
                                            <i class="bi bi-heart-fill me-2"></i>

                                        </span>
                                    </button>
                                </div>

                            </form>

                            <div class="text-center mt-3">
                                <p>لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Preview profile image
                document.getElementById('profile_image').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // You can add image preview functionality here
                            console.log('Image selected:', file.name);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('form');
                    const registerBtn = document.getElementById('registerBtn');
                    const btnText = registerBtn.querySelector('.btn-text');
                    const btnLoading = registerBtn.querySelector('.btn-loading');

                    form.addEventListener('submit', function(e) {
                        // أولاً: تأكد من أن الفورم صالح
                        if (!form.checkValidity()) {
                            return; // لو فيه أخطاء خليه يعرضها
                        }

                        // تعطيل الزر بعد الضغط
                        registerBtn.disabled = true;

                        // إخفاء النص الأساسي
                        btnText.classList.add('d-none');

                        // إظهار نص التحميل
                        btnLoading.classList.remove('d-none');
                    });
                });
            </script>
        @endpush
    @endsection
