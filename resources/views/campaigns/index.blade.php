@extends('layouts.app')

@section('title', 'جميع الحملات - نبض الحياة')

@section('content')
    <div class="container">
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
                                <button class="favorite-btn" onclick="toggleFavorite({{ $campaign->id }}, this)">
                                    <i class="bi bi-heart"></i>
                                </button>
                            @endauth

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
                                <p class="card-text flex-grow-1">{{ Str::limit($campaign->description, 100) }}</p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>{{ number_format($campaign->current_amount, 0) }} ر.س</small>
                                        <small>{{ number_format($campaign->target_amount, 0) }} ر.س</small>
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
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>
                                        {{ $campaign->creator->name }}
                                    </small>
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
                fetch(`/campaigns/${campaignId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const icon = button.querySelector('i');
                        if (data.is_favorite) {
                            icon.classList.remove('bi-heart');
                            icon.classList.add('bi-heart-fill');
                            button.classList.add('active');
                        } else {
                            icon.classList.remove('bi-heart-fill');
                            icon.classList.add('bi-heart');
                            button.classList.remove('active');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                    });
            }
        </script>
    @endpush
@endsection
