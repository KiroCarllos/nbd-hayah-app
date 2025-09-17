@extends('layouts.app')

@section('title', 'إنشاء حساب - نبض الحياة')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4>إنشاء حساب جديد</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">رقم الموبايل <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('mobile') is-invalid @enderror" 
                                           id="mobile" 
                                           name="mobile" 
                                           value="{{ old('mobile') }}" 
                                           required>
                                    @error('mobile')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">الصورة الشخصية <span class="text-danger">*</span></label>
                                    <input type="file" 
                                           class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" 
                                           name="profile_image" 
                                           accept="image/*" 
                                           required>
                                    @error('profile_image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">الحد الأقصى: 2 ميجابايت. الأنواع المدعومة: JPG, PNG, GIF</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
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
                                    <div class="form-text">يجب أن تكون 8 أحرف على الأقل</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" target="_blank">الشروط والأحكام</a> و <a href="#" target="_blank">سياسة الخصوصية</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                إنشاء الحساب
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
@endpush
@endsection
