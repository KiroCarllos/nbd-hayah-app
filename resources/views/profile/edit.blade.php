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
                                @if ($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                                        class="rounded-circle mb-3" width="120" height="120"
                                        style="object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle text-muted mb-3" style="font-size: 7rem;"></i>
                                @endif
                            </div>

                            <!-- Profile Image Upload -->
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">الصورة الشخصية</label>
                                <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                    id="profile_image" name="profile_image" accept="image/*">
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label for="mobile" class="form-label">رقم الهاتف <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                    id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
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
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
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
                                <p><strong>رصيد المحفظة:</strong> {{ number_format($user->wallet_balance, 2) }} ج.م</p>
                                <p><strong>نوع الحساب:</strong> {{ $user->is_admin ? 'مدير' : 'مستخدم عادي' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Password Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>
                            <i class="bi bi-shield-lock me-2"></i>
                            إدارة كلمة مرور المحفظة
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($user->hasWalletPassword())
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="bi bi-shield-check me-2"></i>
                                <div>
                                    تم تعيين كلمة مرور للمحفظة. محفظتك محمية بكلمة مرور.
                                </div>
                            </div>

                            <!-- Change Wallet Password Form -->
                            <form id="changeWalletPasswordForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="current_wallet_password" class="form-label">كلمة مرور المحفظة
                                                الحالية</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" class="form-control" id="current_wallet_password"
                                                    name="current_wallet_password" placeholder="123456"
                                                    pattern="[0-9]{6}" maxlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleCurrentWalletPassword">
                                                    <i class="bi bi-eye" id="toggleCurrentWalletPasswordIcon"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="currentWalletPasswordError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="new_wallet_password_change" class="form-label">كلمة مرور المحفظة
                                                الجديدة</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input type="password" class="form-control"
                                                    id="new_wallet_password_change" name="new_wallet_password"
                                                    placeholder="123456" pattern="[0-9]{6}" maxlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleNewWalletPasswordChange">
                                                    <i class="bi bi-eye" id="toggleNewWalletPasswordChangeIcon"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">يجب أن تكون كلمة المرور 6 أحرف على الأقل</div>
                                            <div class="invalid-feedback" id="newWalletPasswordChangeError"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_wallet_password_change" class="form-label">تأكيد كلمة مرور المحفظة
                                        الجديدة</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" class="form-control" id="confirm_wallet_password_change"
                                            name="confirm_wallet_password" placeholder="123456" pattern="[0-9]{6}"
                                            maxlength="6" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="toggleConfirmWalletPasswordChange">
                                            <i class="bi bi-eye" id="toggleConfirmWalletPasswordChangeIcon"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="confirmWalletPasswordChangeError"></div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning" id="changeWalletPasswordBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-2"
                                            id="changeWalletPasswordSpinner"></span>
                                        <i class="bi bi-shield-exclamation me-2"></i>
                                        تغيير كلمة مرور المحفظة
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-shield-exclamation me-2"></i>
                                <div>
                                    لم يتم تعيين كلمة مرور للمحفظة. يُنصح بتعيين كلمة مرور لحماية محفظتك.
                                </div>
                            </div>

                            <!-- Set Wallet Password Form -->
                            <form id="setWalletPasswordProfileForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="new_wallet_password_set" class="form-label">كلمة مرور المحفظة
                                                الجديدة</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" class="form-control" id="new_wallet_password_set"
                                                    name="new_wallet_password" placeholder="123456" pattern="[0-9]{6}"
                                                    maxlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleNewWalletPasswordSet">
                                                    <i class="bi bi-eye" id="toggleNewWalletPasswordSetIcon"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">يجب أن تكون كلمة المرور 6 أرقام فقط</div>
                                            <div class="invalid-feedback" id="newWalletPasswordSetError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_wallet_password_set" class="form-label">تأكيد كلمة مرور
                                                المحفظة</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock-fill"></i>
                                                </span>
                                                <input type="password" class="form-control"
                                                    id="confirm_wallet_password_set" name="confirm_wallet_password"
                                                    placeholder="123456" pattern="[0-9]{6}" maxlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleConfirmWalletPasswordSet">
                                                    <i class="bi bi-eye" id="toggleConfirmWalletPasswordSetIcon"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="confirmWalletPasswordSetError"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <small>
                                            <strong>مهم:</strong> احتفظ بكلمة مرور المحفظة في مكان آمن. ستحتاجها لجميع
                                            العمليات المالية مثل الشحن والتبرع.
                                        </small>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success" id="setWalletPasswordProfileBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-2"
                                            id="setWalletPasswordProfileSpinner"></span>
                                        <i class="bi bi-shield-plus me-2"></i>
                                        تعيين كلمة مرور المحفظة
                                    </button>
                                </div>
                            </form>
                        @endif
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

            // Wallet password toggle functions
            function setupWalletPasswordToggle(toggleId, inputId, iconId) {
                const toggle = document.getElementById(toggleId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (toggle && input && icon) {
                    toggle.addEventListener('click', function() {
                        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);
                        icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
                    });
                }
            }

            // Setup all wallet password toggles
            setupWalletPasswordToggle('toggleCurrentWalletPassword', 'current_wallet_password',
                'toggleCurrentWalletPasswordIcon');
            setupWalletPasswordToggle('toggleNewWalletPasswordChange', 'new_wallet_password_change',
                'toggleNewWalletPasswordChangeIcon');
            setupWalletPasswordToggle('toggleConfirmWalletPasswordChange', 'confirm_wallet_password_change',
                'toggleConfirmWalletPasswordChangeIcon');
            setupWalletPasswordToggle('toggleNewWalletPasswordSet', 'new_wallet_password_set',
                'toggleNewWalletPasswordSetIcon');
            setupWalletPasswordToggle('toggleConfirmWalletPasswordSet', 'confirm_wallet_password_set',
                'toggleConfirmWalletPasswordSetIcon');

            // Change wallet password form
            const changeWalletPasswordForm = document.getElementById('changeWalletPasswordForm');
            if (changeWalletPasswordForm) {
                changeWalletPasswordForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('changeWalletPasswordBtn');
                    const spinner = document.getElementById('changeWalletPasswordSpinner');
                    const currentPassword = document.getElementById('current_wallet_password').value;
                    const newPassword = document.getElementById('new_wallet_password_change').value;
                    const confirmPassword = document.getElementById('confirm_wallet_password_change').value;

                    // Clear previous errors
                    document.getElementById('currentWalletPasswordError').textContent = '';
                    document.getElementById('newWalletPasswordChangeError').textContent = '';
                    document.getElementById('confirmWalletPasswordChangeError').textContent = '';
                    document.getElementById('current_wallet_password').classList.remove('is-invalid');
                    document.getElementById('new_wallet_password_change').classList.remove('is-invalid');
                    document.getElementById('confirm_wallet_password_change').classList.remove('is-invalid');

                    let hasError = false;

                    if (!/^\d{6}$/.test(newPassword)) {
                        document.getElementById('newWalletPasswordChangeError').textContent =
                            'كلمة المرور يجب أن تكون 6 أرقام فقط';
                        document.getElementById('new_wallet_password_change').classList.add('is-invalid');
                        hasError = true;
                    }

                    if (newPassword !== confirmPassword) {
                        document.getElementById('confirmWalletPasswordChangeError').textContent =
                            'كلمة المرور غير متطابقة';
                        document.getElementById('confirm_wallet_password_change').classList.add('is-invalid');
                        hasError = true;
                    }

                    if (hasError) return;

                    // Show loading state
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    try {
                        const formData = new FormData();
                        formData.append('current_wallet_password', currentPassword);
                        formData.append('new_wallet_password', newPassword);
                        formData.append('confirm_wallet_password', confirmPassword);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        const response = await fetch('{{ route('wallet.change-password') }}', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('تم تغيير كلمة مرور المحفظة بنجاح');
                            changeWalletPasswordForm.reset();
                        } else {
                            if (data.errors) {
                                if (data.errors.current_wallet_password) {
                                    document.getElementById('currentWalletPasswordError').textContent = data.errors
                                        .current_wallet_password[0];
                                    document.getElementById('current_wallet_password').classList.add('is-invalid');
                                }
                            } else {
                                alert(data.message || 'حدث خطأ في تغيير كلمة مرور المحفظة');
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('حدث خطأ في تغيير كلمة مرور المحفظة');
                    } finally {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });
            }

            // Set wallet password form
            const setWalletPasswordProfileForm = document.getElementById('setWalletPasswordProfileForm');
            if (setWalletPasswordProfileForm) {
                setWalletPasswordProfileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('setWalletPasswordProfileBtn');
                    const spinner = document.getElementById('setWalletPasswordProfileSpinner');
                    const newPassword = document.getElementById('new_wallet_password_set').value;
                    const confirmPassword = document.getElementById('confirm_wallet_password_set').value;

                    // Clear previous errors
                    document.getElementById('newWalletPasswordSetError').textContent = '';
                    document.getElementById('confirmWalletPasswordSetError').textContent = '';
                    document.getElementById('new_wallet_password_set').classList.remove('is-invalid');
                    document.getElementById('confirm_wallet_password_set').classList.remove('is-invalid');

                    let hasError = false;

                    if (!/^\d{6}$/.test(newPassword)) {
                        document.getElementById('newWalletPasswordSetError').textContent =
                            'كلمة المرور يجب أن تكون 6 أرقام فقط';
                        document.getElementById('new_wallet_password_set').classList.add('is-invalid');
                        hasError = true;
                    }

                    if (newPassword !== confirmPassword) {
                        document.getElementById('confirmWalletPasswordSetError').textContent =
                            'كلمة المرور غير متطابقة';
                        document.getElementById('confirm_wallet_password_set').classList.add('is-invalid');
                        hasError = true;
                    }

                    if (hasError) return;

                    // Show loading state
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');

                    try {
                        const formData = new FormData();
                        formData.append('new_wallet_password', newPassword);
                        formData.append('confirm_wallet_password', confirmPassword);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        const response = await fetch('{{ route('wallet.set-password') }}', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('تم تعيين كلمة مرور المحفظة بنجاح');
                            location.reload(); // Reload to show the change password form
                        } else {
                            alert(data.message || 'حدث خطأ في تعيين كلمة مرور المحفظة');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('حدث خطأ في تعيين كلمة مرور المحفظة');
                    } finally {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    }
                });
            }
        </script>
    @endpush
@endsection
