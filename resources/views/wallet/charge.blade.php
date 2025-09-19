@extends('layouts.app')

@section('title', 'شحن المحفظة - نبض الحياة')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>شحن المحفظة</h4>
                        <p class="text-muted mb-0">اشحن محفظتك لتتمكن من التبرع للحملات</p>
                    </div>
                    <div class="card-body">
                        <!-- Current Balance -->
                        <div class="alert alert-info text-center">
                            <i class="bi bi-wallet2 me-2"></i>
                            رصيدك الحالي: <strong>@currency(auth()->user()?->wallet_balance)</strong>
                        </div>

                        <form method="POST" action="{{ route('wallet.charge.process') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="amount" class="form-label">مبلغ الشحن (ج.م) <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" value="{{ old('amount') }}" min="1" step="0.01"
                                    required autofocus>
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">الحد الأدنى: 1 ج.م</div>
                            </div>

                            <!-- Quick Amount Buttons -->
                            <div class="mb-4">
                                <label class="form-label">مبالغ سريعة:</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(50)">50
                                        ج.م</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="setAmount(100)">100 ج.م</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="setAmount(200)">200 ج.م</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="setAmount(500)">500 ج.م</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="setAmount(1000)">1000 ج.م</button>
                                </div>
                            </div>

                            <!-- Payment Method Info -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-credit-card me-2"></i>
                                        طريقة الدفع
                                    </h6>
                                    <p class="card-text mb-2">سيتم توجيهك إلى بوابة الدفع الآمنة URWAY لإتمام عملية الشحن
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-check text-success me-2"></i>
                                        <small class="text-muted">دفع آمن ومشفر</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        أوافق على <a href="#" target="_blank">شروط وأحكام</a> استخدام المحفظة
                                        الإلكترونية
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-credit-card me-2"></i>
                                    متابعة للدفع
                                </button>
                                <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    العودة للمحفظة
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">معلومات مهمة</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                سيتم إضافة المبلغ فوراً بعد نجاح عملية الدفع
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                يمكنك استخدام الرصيد للتبرع في أي حملة
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                جميع المعاملات مسجلة ويمكن مراجعتها
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-info-circle text-info me-2"></i>
                                في حالة وجود مشكلة، تواصل مع الدعم الفني
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function setAmount(amount) {
                document.getElementById('amount').value = amount;
            }

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('amount').value);
                const terms = document.getElementById('terms').checked;

                if (!amount || amount < 1 || amount > 10000) {
                    e.preventDefault();
                    alert('يرجى إدخال مبلغ صحيح بين 1 و 10,000 ريال');
                    return;
                }

                if (!terms) {
                    e.preventDefault();
                    alert('يرجى الموافقة على الشروط والأحكام');
                    return;
                }

                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري التحويل...';
                submitBtn.disabled = true;
            });
        </script>
    @endpush
@endsection
