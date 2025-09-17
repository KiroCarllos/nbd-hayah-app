@extends('layouts.app')

@section('title', 'المفضلة - نبض الحياة')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>حملاتي المفضلة</h2>
                    <span class="badge bg-primary fs-6">{{ $favorites->total() }} حملة</span>
                </div>

                @if ($favorites->count() > 0)
                    <div class="row">
                        @foreach ($favorites as $favorite)
                            @php $campaign = $favorite->campaign; @endphp
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <!-- Favorite Button -->
                                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                        <button class="btn btn-sm btn-danger favorite-btn active"
                                            data-campaign-id="{{ $campaign->id }}" data-is-favorited="true">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </div>

                                    @if ($campaign->images && count($campaign->images) > 0)
                                        <img src="{{ asset('storage/' . $campaign->images[0]) }}" class="card-img-top"
                                            alt="{{ $campaign->title }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary d-flex align-items-center justify-content-center"
                                            style="height: 200px;">
                                            <i class="bi bi-heart-fill text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $campaign->title }}</h5>
                                        <p class="card-text text-muted flex-grow-1">
                                            {{ \Illuminate\Support\Str::limit($campaign->description, 100) }}
                                        </p>

                                        <!-- Progress -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">المحصل: @currency($campaign->current_amount)</small>
                                                <small class="text-muted">الهدف: @currency($campaign->target_amount)</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $campaign->progress_percentage }}%"
                                                    aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small
                                                class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%
                                                مكتمل</small>
                                        </div>

                                        <!-- Campaign Info -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                @if ($campaign->creator->profile_image)
                                                    <img src="{{ asset('storage/' . $campaign->creator->profile_image) }}"
                                                        alt="Creator" class="rounded-circle me-2" width="24"
                                                        height="24">
                                                @else
                                                    <i class="bi bi-person-circle me-2"></i>
                                                @endif
                                                <small class="text-muted">{{ $campaign->creator->name }}</small>
                                            </div>
                                            <small class="text-muted">{{ $campaign->created_at->diffForHumans() }}</small>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary">
                                                <i class="bi bi-eye me-2"></i>
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $favorites->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
                        <h3 class="mt-3 text-muted">لا توجد حملات مفضلة</h3>
                        <p class="text-muted">ابدأ بإضافة الحملات التي تهمك إلى المفضلة</p>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            تصفح الحملات
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle favorite toggle
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const campaignId = this.dataset.campaignId;
                        const isCurrentlyFavorited = this.dataset.isFavorited === 'true';

                        // Disable button during request
                        this.disabled = true;

                        fetch(`/campaigns/${campaignId}/favorite`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (data.is_favorited) {
                                        // Added to favorites
                                        this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                                        this.className = 'btn btn-sm btn-danger favorite-btn';
                                        this.dataset.isFavorited = 'true';
                                    } else {
                                        // Removed from favorites - remove the card from view
                                        this.closest('.col-md-6').remove();

                                        // Update favorites count
                                        const badge = document.querySelector(
                                            '.badge.bg-primary.fs-6');
                                        if (badge) {
                                            const currentCount = parseInt(badge.textContent);
                                            badge.textContent = `${currentCount - 1} حملة`;
                                        }

                                        // Check if no favorites left
                                        const remainingCards = document.querySelectorAll(
                                            '.col-md-6').length;
                                        if (remainingCards === 0) {
                                            location.reload(); // Reload to show empty state
                                        }
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
                                this.disabled = false;
                            });
                    });
                });
            });

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
