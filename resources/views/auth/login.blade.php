@extends('layouts.app')

@section('title', 'تسجيل الدخول - نبض الحياة')

@section('content')
    <div class="min-vh-100 d-flex align-items-center bg-gradient-mixed">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">
                            <!-- Logo and Title -->
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <img src="{{ asset('logo-text-bottom.png') }}" alt="نبض الحياة" class="logo-large">
                                </div>
                                <h2 class="fw-bold text-dark">مرحباً بعودتك</h2>
                                <p class="text-muted">سجل دخولك لمتابعة رحلة العطاء</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="mobile" class="form-label fw-semibold">رقم الموبايل</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-phone text-muted"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control border-start-0 ps-0 @error('mobile') is-invalid @enderror"
                                            id="mobile" name="mobile" value="{{ old('mobile') }}"
                                            placeholder="05xxxxxxxx" required autofocus>
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="أدخل كلمة المرور" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label text-muted" for="remember">تذكرني</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold" id="loginBtn">
                                    <span class="btn-text">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        تسجيل الدخول
                                    </span>
                                    <span class="btn-loading d-none">
                                        جاري تسجيل الدخول...
                                        <i class="bi bi-heart-fill me-2"></i>
                                    </span>
                                </button>

                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">ليس لديك حساب؟</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                                    إنشاء حساب جديد
                                    <i class="bi bi-person-plus me-2"></i>

                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-white text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>
                            العودة للرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            const btnLoading = loginBtn.querySelector('.btn-loading');

            form.addEventListener('submit', function(e) {
                // أولاً اتأكد أن الفورم صحيح
                if (!form.checkValidity()) {
                    return; // لو فيه أخطاء خليه يعرضها
                }

                // تعطيل الزر
                loginBtn.disabled = true;

                // إخفاء النص الأساسي
                btnText.classList.add('d-none');

                // إظهار نص "جاري تسجيل الدخول"
                btnLoading.classList.remove('d-none');
            });
        });
    </script>
@endpush
