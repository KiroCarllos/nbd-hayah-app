@extends('layouts.app')

@section('title', 'التحقق من كلمة مرور المحفظة')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        التحقق من كلمة مرور المحفظة
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-info">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('wallet.verify-password') }}" id="verifyForm">
                        @csrf
                        
                        @if(session('return_url'))
                            <input type="hidden" name="return_url" value="{{ session('return_url') }}">
                        @endif
                        
                        @if(session('return_data'))
                            @foreach(session('return_data') as $key => $value)
                                <input type="hidden" name="return_data[{{ $key }}]" value="{{ $value }}">
                            @endforeach
                        @endif

                        <div class="mb-4">
                            <label for="wallet_password" class="form-label">
                                <i class="bi bi-key me-1"></i>
                                كلمة مرور المحفظة
                            </label>
                            <input type="password" 
                                   class="form-control form-control-lg text-center" 
                                   id="wallet_password" 
                                   name="wallet_password" 
                                   placeholder="123456"
                                   pattern="[0-9]{6}"
                                   maxlength="6"
                                   required>
                            <div class="form-text">أدخل كلمة مرور المحفظة المكونة من 6 أرقام</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                                <i class="bi bi-check-circle me-2"></i>
                                التحقق والمتابعة
                            </button>
                            <a href="{{ route('wallet.charge') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right me-2"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('verifyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('verifyBtn');
    const password = document.getElementById('wallet_password').value;
    
    // Validate password format
    if (!/^\d{6}$/.test(password)) {
        alert('كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري التحقق...';
    submitBtn.disabled = true;
    
    // Submit via AJAX
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to return URL or process the original request
            const returnUrl = form.querySelector('input[name="return_url"]')?.value;
            const returnData = {};
            
            // Collect return data
            form.querySelectorAll('input[name^="return_data["]').forEach(input => {
                const key = input.name.match(/return_data\[(.+)\]/)[1];
                returnData[key] = input.value;
            });
            
            if (returnUrl && Object.keys(returnData).length > 0) {
                // Submit the original form data to the return URL
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = returnUrl;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                tempForm.appendChild(csrfInput);
                
                // Add return data
                Object.keys(returnData).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = returnData[key];
                    tempForm.appendChild(input);
                });
                
                document.body.appendChild(tempForm);
                tempForm.submit();
            } else {
                // Just redirect to wallet charge page
                window.location.href = '{{ route("wallet.charge") }}';
            }
        } else {
            alert(data.message || 'حدث خطأ أثناء التحقق');
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>التحقق والمتابعة';
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء التحقق');
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>التحقق والمتابعة';
        submitBtn.disabled = false;
    });
});

// Auto-focus on password field
document.getElementById('wallet_password').focus();

// Only allow numbers
document.getElementById('wallet_password').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
