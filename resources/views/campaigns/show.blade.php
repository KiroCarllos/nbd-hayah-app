@extends('layouts.app')

@section('title', $campaign->title . ' - نبض الحياة')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <!-- Campaign Images -->
                @if ($campaign->images && count($campaign->images) > 0)
                    <div class="mb-4">
                        @if (count($campaign->images) > 1)
                            <div id="campaignImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($campaign->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <a href="{{ asset('storage/' . $image) }}" class="glightbox d-block"
                                                data-gallery="campaign-images" data-title="{{ $campaign->title }}"
                                                data-description="صورة {{ $index + 1 }} من {{ count($campaign->images) }}">
                                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 rounded"
                                                    alt="{{ $campaign->title }}"
                                                    style="height: 400px; object-fit: cover; cursor: pointer;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#campaignImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">السابق</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#campaignImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">التالي</span>
                                </button>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $campaign->images[0]) }}" class="glightbox d-block"
                                data-gallery="campaign-images" data-title="{{ $campaign->title }}"
                                data-description="صورة الحملة">
                                <img src="{{ asset('storage/' . $campaign->images[0]) }}" class="img-fluid rounded"
                                    alt="{{ $campaign->title }}"
                                    style="width: 100%; height: 400px; object-fit: cover; cursor: pointer;">
                            </a>
                        @endif
                    </div>
                @else
                    <div class="bg-primary d-flex align-items-center justify-content-center rounded mb-4"
                        style="height: 400px;">
                        <i class="bi bi-heart-fill text-white" style="font-size: 6rem;"></i>
                    </div>
                @endif

                <!-- Campaign Details -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="card-title">{{ $campaign->title }}</h1>
                            @auth
                                <button
                                    class="favorite-btn position-static {{ $campaign->isFavoritedBy(auth()->id()) ? 'active' : '' }}"
                                    onclick="toggleFavorite({{ $campaign->id }}, this)">
                                    <i class="bi bi-heart{{ $campaign->isFavoritedBy(auth()->id()) ? '-fill' : '' }}"></i>
                                </button>
                            @endauth
                        </div>

                        <div class="mb-3">
                            @if ($campaign->end_date)
                                <small class="text-muted ms-3">
                                    <i class="bi bi-calendar me-1"></i>
                                    ينتهي في: {{ $campaign->end_date->format('Y/m/d') }}
                                </small>
                            @endif
                        </div>

                        <p class="card-text">{{ $campaign->description }}</p>
                    </div>
                </div>

                <!-- Recent Donations -->
                @if ($campaign->donations->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">آخر التبرعات</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($campaign->donations->take(10) as $donation)
                                <div
                                    class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex align-items-center">
                                        @if ($donation->is_anonymous)
                                            <img src="{{ asset('secret.jpg') }}" alt="متبرع مجهول"
                                                class="rounded-circle me-2" width="30" height="30"
                                                style="object-fit: cover;">
                                        @elseif ($donation->user->profile_image && $donation->user->profile_image !== 'default.png')
                                            <img src="{{ asset('storage/' . $donation->user->profile_image) }}"
                                                alt="Profile" class="rounded-circle me-2" width="30" height="30"
                                                style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('default.png') }}" alt="صورة افتراضية"
                                                class="rounded-circle me-2" width="30" height="30"
                                                style="object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $donation->is_anonymous ? 'متبرع مجهول' : $donation->user->name }}</strong>
                                            <small
                                                class="text-muted d-block">{{ $donation->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success">@currency($donation->amount)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Progress Card -->
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title">تقدم الحملة</h5>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">@currency($campaign->current_amount)</span>
                                <span class="text-muted">من @currency($campaign->target_amount)</span>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $campaign->progress_percentage }}%"
                                    aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-success">{{ number_format($campaign->progress_percentage, 1) }}%
                                    مكتمل</small>
                                <small class="text-muted">متبقي: @currency($campaign->remaining_amount)</small>
                            </div>
                        </div>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="mb-0">{{ $campaign->donations->count() }}</h6>
                                    <small class="text-muted">متبرع</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">{{ $campaign->created_at->diffForHumans() }}</h6>
                                <small class="text-muted">تم الإنشاء</small>
                            </div>
                        </div>

                        @auth
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" onclick="showDonationModal()">
                                    <i class="bi bi-heart-fill me-2"></i>
                                    تبرع الآن
                                </button>
                            </div>
                        @else
                            <div class="d-grid">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    سجل دخولك للتبرع
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        <!-- Donation Modal -->
        <div class="modal fade" id="donationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">التبرع للحملة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>رصيد محفظتك الحالي: <strong>@currency(auth()->user()->wallet_balance)</strong></p>

                        @if (auth()->user()->wallet_balance <= 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                رصيد محفظتك غير كافي. يرجى شحن المحفظة أولاً.
                            </div>
                            <div class="d-grid">
                                <a href="{{ url('/wallet') }}" class="btn btn-warning">
                                    <i class="bi bi-wallet2 me-2"></i>
                                    شحن المحفظة
                                </a>
                            </div>
                        @else
                            <form id="donationForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="amount" class="form-label">مبلغ التبرع ({!! \App\Helpers\CurrencyHelper::getSymbol() !!})</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="1"
                                        max="{{ auth()->user()->wallet_balance }}" required>
                                    <div class="form-text">الحد الأقصى: @currency(auth()->user()->wallet_balance)</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_anonymous"
                                            name="is_anonymous">
                                        <label class="form-check-label" for="is_anonymous">
                                            تبرع مجهول (لن يظهر اسمك)
                                        </label>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    @if (auth()->user()->wallet_balance > 0)
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-primary" onclick="submitDonation()">
                                <i class="bi bi-heart-fill me-2"></i>
                                تأكيد التبرع
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endauth

    @push('scripts')
        <script>
            function toggleFavorite(campaignId, button) {
                // Disable button during request
                button.disabled = true;

                fetch(`/campaigns/${campaignId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const icon = button.querySelector('i');
                            if (data.is_favorited) {
                                icon.classList.remove('bi-heart');
                                icon.classList.add('bi-heart-fill');
                                button.classList.add('active');
                            } else {
                                icon.classList.remove('bi-heart-fill');
                                icon.classList.add('bi-heart');
                                button.classList.remove('active');
                            }

                            // Show success message
                            showAlert(data.message, 'success');
                        } else {
                            showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
            }

            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', alertHtml);

                // Auto dismiss after 3 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 3000);
            }

            @auth

            function showDonationModal() {
                const modal = new bootstrap.Modal(document.getElementById('donationModal'));
                modal.show();
            }

            function submitDonation() {
                const form = document.getElementById('donationForm');
                const formData = new FormData(form);
                const submitBtn = document.querySelector('#donationModal .btn-primary');

                // Show loading state
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري المعالجة...';
                submitBtn.disabled = true;

                fetch(`/campaigns/{{ $campaign->id }}/donate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.error || 'حدث خطأ، يرجى المحاولة مرة أخرى');
                            // Reset button
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            }
            @endauth
        </script>
    @endpush
@endsection
