class WalletPasswordManager {
    constructor() {
        this.currentAction = null;
        this.currentCallback = null;
        this.csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    validatePassword(password) {
        if (!password) {
            return "كلمة مرور المحفظة مطلوبة";
        }

        if (!/^\d{6}$/.test(password)) {
            return "كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط";
        }

        return null;
    }

    async verifyWalletPassword(callback, action = "العملية") {
        this.currentCallback = callback;
        this.currentAction = action;

        try {
            // Check if user has wallet password
            const response = await fetch("/wallet/has-password", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
            });

            const data = await response.json();

            if (data.success && data.has_password) {
                // Show password verification modal
                this.showPasswordModal();
            } else {
                // Show set password modal
                this.showSetPasswordModal();
            }
        } catch (error) {
            console.error("Error checking wallet password:", error);
            this.showError("حدث خطأ في التحقق من كلمة مرور المحفظة");
        }
    }

    showPasswordModal() {
        const modal = new bootstrap.Modal(
            document.getElementById("walletPasswordModal")
        );
        modal.show();
    }

    showSetPasswordModal() {
        const modal = new bootstrap.Modal(
            document.getElementById("setWalletPasswordModal")
        );
        modal.show();
    }

    async handleWalletPasswordSubmit(form) {
        const submitBtn = document.getElementById("confirmWalletPasswordBtn");
        const spinner = document.getElementById("walletPasswordSpinner");
        const passwordInput = document.getElementById("walletPassword");
        const errorDiv = document.getElementById("walletPasswordError");

        // Validate password format
        const validationError = this.validatePassword(passwordInput.value);
        if (validationError) {
            this.showFieldError(errorDiv, validationError);
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove("d-none");

        try {
            const formData = new FormData(form);

            const response = await fetch("/wallet/verify-password", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                // Hide modal and execute callback
                bootstrap.Modal.getInstance(
                    document.getElementById("walletPasswordModal")
                ).hide();
                if (this.currentCallback) {
                    this.currentCallback();
                }
            } else {
                // Show error
                passwordInput.classList.add("is-invalid");
                errorDiv.textContent =
                    data.message || "كلمة مرور المحفظة غير صحيحة";
            }
        } catch (error) {
            console.error("Error verifying wallet password:", error);
            passwordInput.classList.add("is-invalid");
            errorDiv.textContent = "حدث خطأ في التحقق من كلمة المرور";
        } finally {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.classList.add("d-none");
        }
    }

    /**
     * Handle set wallet password form submission
     * @param {HTMLFormElement} form
     */
    async handleSetWalletPasswordSubmit(form) {
        const submitBtn = document.getElementById("setWalletPasswordBtn");
        const spinner = document.getElementById("setWalletPasswordSpinner");

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove("d-none");

        try {
            const formData = new FormData(form);

            const response = await fetch("/wallet/set-password", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                // Hide modal and execute callback
                bootstrap.Modal.getInstance(
                    document.getElementById("setWalletPasswordModal")
                ).hide();
                this.showSuccess("تم تعيين كلمة مرور المحفظة بنجاح");
                if (this.currentCallback) {
                    this.currentCallback();
                }
            } else {
                this.showError(
                    data.message || "حدث خطأ في تعيين كلمة مرور المحفظة"
                );
            }
        } catch (error) {
            console.error("Error setting wallet password:", error);
            this.showError("حدث خطأ في تعيين كلمة مرور المحفظة");
        } finally {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.classList.add("d-none");
        }
    }

    /**
     * Get authentication token (for API calls)
     * Not needed for web routes as they use session-based auth
     */
    getAuthToken() {
        return null;
    }

    /**
     * Show success message
     */
    showSuccess(message) {
        // You can customize this based on your notification system
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "success",
                title: "نجح!",
                text: message,
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            alert(message);
        }
    }

    /**
     * Show error message
     */
    showError(message) {
        // You can customize this based on your notification system
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "error",
                title: "خطأ!",
                text: message,
            });
        } else {
            alert(message);
        }
    }
}

// Create global instance
window.walletPasswordManager = new WalletPasswordManager();

// Global functions for modal forms
window.handleWalletPasswordSubmit = function (form) {
    window.walletPasswordManager.handleWalletPasswordSubmit(form);
};

window.handleSetWalletPasswordSubmit = function (form) {
    window.walletPasswordManager.handleSetWalletPasswordSubmit(form);
};

// Helper function to verify wallet password before action
window.verifyWalletPassword = function (callback, action) {
    window.walletPasswordManager.verifyWalletPassword(callback, action);
};
