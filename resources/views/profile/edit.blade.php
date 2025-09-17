@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي - نبض الحياة')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>تعديل الملف الشخصي</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Current Profile Image -->
                        <div class="text-center mb-4">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle text-muted mb-3" style="font-size: 7rem;"></i>
                            @endif
                        </div>

                        <!-- Profile Image Upload -->
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">الصورة الشخصية</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
                            @error('profile_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">اختياري - يمكنك تغيير الصورة الشخصية</div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="mb-3">
                            <label for="mobile" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                            @error('mobile')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Password Change Section -->
                        <h5>تغيير كلمة المرور</h5>
                        <p class="text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</p>

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary me-md-2">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الحساب</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y/m/d') }}</p>
                            <p><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>رصيد المحفظة:</strong> {{ number_format($user->wallet_balance, 2) }} ر.س</p>
                            <p><strong>نوع الحساب:</strong> {{ $user->is_admin ? 'مدير' : 'مستخدم عادي' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview image before upload
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Find the current image or icon and replace it
            const currentImage = document.querySelector('.rounded-circle, .bi-person-circle');
            if (currentImage) {
                if (currentImage.tagName === 'IMG') {
                    currentImage.src = e.target.result;
                } else {
                    // Replace icon with image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded-circle mb-3';
                    img.width = 120;
                    img.height = 120;
                    img.style.objectFit = 'cover';
                    currentImage.parentNode.replaceChild(img, currentImage);
                }
            }
        };
        reader.readAsDataURL(file);
    }
});

// Password validation
document.getElementById('password').addEventListener('input', function() {
    const currentPassword = document.getElementById('current_password');
    if (this.value.length > 0) {
        currentPassword.required = true;
    } else {
        currentPassword.required = false;
    }
});
</script>
@endpush
@endsection
