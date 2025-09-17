@extends('layouts.app')

@section('title', 'تسجيل الدخول - نبض الحياة')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>تسجيل الدخول</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="mobile" class="form-label">رقم الموبايل</label>
                            <input type="text" 
                                   class="form-control @error('mobile') is-invalid @enderror" 
                                   id="mobile" 
                                   name="mobile" 
                                   value="{{ old('mobile') }}" 
                                   required 
                                   autofocus>
                            @error('mobile')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                تسجيل الدخول
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
