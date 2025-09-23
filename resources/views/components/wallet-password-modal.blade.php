<!-- Wallet Password Modal -->
<div class="modal fade" id="walletPasswordModal" tabindex="-1" aria-labelledby="walletPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="walletPasswordModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>
                    تأكيد كلمة مرور المحفظة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="walletPasswordForm">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-wallet2 display-4 text-primary"></i>
                        <p class="mt-3 text-muted">لحماية محفظتك، يرجى إدخال كلمة مرور المحفظة للمتابعة</p>
                    </div>

                    <div class="mb-3">
                        <label for="walletPassword" class="form-label">كلمة مرور المحفظة</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="walletPassword" name="wallet_password" 
                                   placeholder="أدخل كلمة مرور المحفظة" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleWalletPassword">
                                <i class="bi bi-eye" id="toggleWalletPasswordIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="walletPasswordError"></div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <small>
                                إذا لم تقم بتعيين كلمة مرور للمحفظة من قبل، يمكنك تعيينها من صفحة البروفايل.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="confirmWalletPasswordBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="walletPasswordSpinner"></span>
                        تأكيد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Set Wallet Password Modal (for first time users) -->
<div class="modal fade" id="setWalletPasswordModal" tabindex="-1" aria-labelledby="setWalletPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setWalletPasswordModalLabel">
                    <i class="bi bi-shield-plus me-2"></i>
                    تعيين كلمة مرور المحفظة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="setWalletPasswordForm">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-exclamation display-4 text-warning"></i>
                        <p class="mt-3 text-muted">لم تقم بتعيين كلمة مرور للمحفظة بعد. يرجى تعيين كلمة مرور لحماية محفظتك.</p>
                    </div>

                    <div class="mb-3">
                        <label for="newWalletPassword" class="form-label">كلمة مرور المحفظة الجديدة</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="newWalletPassword" name="new_wallet_password" 
                                   placeholder="أدخل كلمة مرور قوية" required minlength="6">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewWalletPassword">
                                <i class="bi bi-eye" id="toggleNewWalletPasswordIcon"></i>
                            </button>
                        </div>
                        <div class="form-text">يجب أن تكون كلمة المرور 6 أحرف على الأقل</div>
                        <div class="invalid-feedback" id="newWalletPasswordError"></div>
                    </div>

                    <div class="mb-3">
                        <label for="confirmWalletPassword" class="form-label">تأكيد كلمة مرور المحفظة</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" class="form-control" id="confirmWalletPassword" name="confirm_wallet_password" 
                                   placeholder="أعد إدخال كلمة المرور" required minlength="6">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmWalletPassword">
                                <i class="bi bi-eye" id="toggleConfirmWalletPasswordIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="confirmWalletPasswordError"></div>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <div>
                            <small>
                                <strong>مهم:</strong> احتفظ بكلمة مرور المحفظة في مكان آمن. ستحتاجها لجميع العمليات المالية.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="setWalletPasswordBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="setWalletPasswordSpinner"></span>
                        تعيين كلمة المرور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    function setupPasswordToggle(toggleId, inputId, iconId) {
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

    setupPasswordToggle('toggleWalletPassword', 'walletPassword', 'toggleWalletPasswordIcon');
    setupPasswordToggle('toggleNewWalletPassword', 'newWalletPassword', 'toggleNewWalletPasswordIcon');
    setupPasswordToggle('toggleConfirmWalletPassword', 'confirmWalletPassword', 'toggleConfirmWalletPasswordIcon');

    // Wallet password form validation
    const walletPasswordForm = document.getElementById('walletPasswordForm');
    if (walletPasswordForm) {
        walletPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // This will be handled by the parent page
            if (window.handleWalletPasswordSubmit) {
                window.handleWalletPasswordSubmit(this);
            }
        });
    }

    // Set wallet password form validation
    const setWalletPasswordForm = document.getElementById('setWalletPasswordForm');
    if (setWalletPasswordForm) {
        setWalletPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newWalletPassword').value;
            const confirmPassword = document.getElementById('confirmWalletPassword').value;
            
            // Clear previous errors
            document.getElementById('newWalletPasswordError').textContent = '';
            document.getElementById('confirmWalletPasswordError').textContent = '';
            document.getElementById('newWalletPassword').classList.remove('is-invalid');
            document.getElementById('confirmWalletPassword').classList.remove('is-invalid');
            
            let hasError = false;
            
            if (newPassword.length < 6) {
                document.getElementById('newWalletPasswordError').textContent = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
                document.getElementById('newWalletPassword').classList.add('is-invalid');
                hasError = true;
            }
            
            if (newPassword !== confirmPassword) {
                document.getElementById('confirmWalletPasswordError').textContent = 'كلمة المرور غير متطابقة';
                document.getElementById('confirmWalletPassword').classList.add('is-invalid');
                hasError = true;
            }
            
            if (!hasError) {
                // This will be handled by the parent page
                if (window.handleSetWalletPasswordSubmit) {
                    window.handleSetWalletPasswordSubmit(this);
                }
            }
        });
    }

    // Reset forms when modals are hidden
    document.getElementById('walletPasswordModal')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('walletPasswordForm')?.reset();
        document.getElementById('walletPassword')?.classList.remove('is-invalid');
        document.getElementById('walletPasswordError').textContent = '';
    });

    document.getElementById('setWalletPasswordModal')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('setWalletPasswordForm')?.reset();
        document.getElementById('newWalletPassword')?.classList.remove('is-invalid');
        document.getElementById('confirmWalletPassword')?.classList.remove('is-invalid');
        document.getElementById('newWalletPasswordError').textContent = '';
        document.getElementById('confirmWalletPasswordError').textContent = '';
    });
});
</script>
