@extends('layouts.app')

@section('title', 'تسجيل الدخول - نبض الحياة')

@section('content')
    <div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">
                            <!-- Logo and Title -->
                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-heart-fill text-primary" style="font-size: 2.5rem;"></i>
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

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    تسجيل الدخول
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">ليس لديك حساب؟</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                                    <i class="bi bi-person-plus me-2"></i>
                                    إنشاء حساب جديد
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
