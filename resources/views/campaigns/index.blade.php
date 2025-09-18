@extends('layouts.app')

@section('title', 'جميع الحملات - نبض الحياة')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1>جميع الحملات</h1>
                <p class="text-muted">اكتشف الحملات الخيرية وساهم في إحداث فرق في المجتمع</p>
            </div>
        </div>

        @if ($campaigns->count() > 0)
            <div class="row">
                @foreach ($campaigns as $campaign)
                    <div class="col-md-4 mb-4">
                        <div class="card campaign-card h-100 position-relative">
                            <!-- Favorite Button -->
                            @auth
                                <button class="favorite-btn {{ $campaign->isFavoritedBy(auth()->id()) ? 'active' : '' }}"
                                    onclick="toggleFavorite({{ $campaign->id }}, this)">
                                    <i class="bi bi-heart{{ $campaign->isFavoritedBy(auth()->id()) ? '-fill' : '' }}"></i>
                                </button>
                            @endauth

                            @if ($campaign->images && count($campaign->images) > 0)
                                <a href="{{ asset('storage/' . $campaign->images[0]) }}" class="glightbox"
                                    data-gallery="campaigns-index" data-title="{{ $campaign->title }}"
                                    data-description="{{ Str::limit($campaign->description, 200) }}">
                                    <img src="{{ asset('storage/' . $campaign->images[0]) }}" class="card-img-top"
                                        alt="{{ $campaign->title }}"
                                        style="height: 200px; object-fit: cover; cursor: pointer;">
                                </a>
                            @else
                                <div class="bg-primary d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="bi bi-heart-fill text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $campaign->title }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($campaign->description, 100) }}</p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>@currency($campaign->current_amount)</small>
                                        <small>@currency($campaign->target_amount)</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $campaign->progress_percentage }}%"
                                            aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%
                                        مكتمل</small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>
                                        عرض التفاصيل
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    {{ $campaigns->links() }}
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        لا توجد حملات متاحة حالياً
                    </div>
                </div>
            </div>
        @endif
    </div>

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
        </script>
    @endpush
@endsection
