@extends('layouts.admin')

@section('title', 'تفاصيل الحملة - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $campaign->title }}</h1>
        <div>
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-2"></i>
                تعديل
            </a>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Campaign Images -->
            @if ($campaign->images && count($campaign->images) > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">صور الحملة</h5>
                    </div>
                    <div class="card-body">
                        @if (count($campaign->images) > 1)
                            <div id="campaignImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($campaign->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 rounded"
                                                alt="{{ $campaign->title }}" style="height: 400px; object-fit: cover;">
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
                                <div class="carousel-indicators">
                                    @foreach ($campaign->images as $index => $image)
                                        <button type="button" data-bs-target="#campaignImagesCarousel"
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="الصورة {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $campaign->images[0]) }}" class="img-fluid rounded"
                                alt="{{ $campaign->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                        @endif
                    </div>
                </div>
            @endif

            <!-- Campaign Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">وصف الحملة</h5>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $campaign->description }}</p>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">آخر التبرعات ({{ $campaign->donations->count() }})</h5>
                </div>
                <div class="card-body">
                    @if ($campaign->donations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>المتبرع</th>
                                        <th>المبلغ</th>
                                        <th>النوع</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaign->donations->take(10) as $donation)
                                        <tr>
                                            <td>
                                                @if ($donation->is_anonymous)
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3"
                                                            style="width: 32px; height: 32px;">
                                                            <i class="bi bi-eye-slash text-white"></i>
                                                        </div>
                                                        <span>متبرع مجهول</span>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        @if ($donation->user->profile_image)
                                                            <img src="{{ asset('storage/' . $donation->user->profile_image) }}"
                                                                alt="Profile" class="rounded-circle me-3" width="32"
                                                                height="32" style="object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle me-3"
                                                                style="width: 32px; height: 32px;">
                                                                <i class="bi bi-person text-white"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $donation->user->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $donation->user->mobile }}</small>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-success fs-6">{{ number_format($donation->amount, 2) }}
                                                    ج.م</span>
                                            </td>
                                            <td>
                                                @if ($donation->is_anonymous)
                                                    <span class="badge bg-secondary">مجهول</span>
                                                @else
                                                    <span class="badge bg-primary">علني</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $donation->created_at->format('Y/m/d H:i') }}
                                                <br>
                                                <small
                                                    class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($campaign->donations->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.donations.index') }}?campaign={{ $campaign->id }}"
                                    class="btn btn-outline-primary">
                                    عرض جميع التبرعات
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-gift text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">لا توجد تبرعات بعد</h5>
                            <p class="text-muted">كن أول من يتبرع لهذه الحملة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Campaign Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">إحصائيات الحملة</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h3 class="text-success">{{ number_format($campaign->current_amount, 0) }}</h3>
                            <small class="text-muted">ج.م محصل</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-primary">{{ number_format($campaign->target_amount, 0) }}</h3>
                            <small class="text-muted">ج.م مستهدف</small>
                        </div>
                    </div>

                    <div class="progress mb-3" style="height: 15px;">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $campaign->progress_percentage }}%"
                            aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($campaign->progress_percentage, 1) }}%
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-info">{{ $campaign->donations->count() }}</h4>
                            <small class="text-muted">متبرع</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">
                                {{ number_format($campaign->target_amount - $campaign->current_amount, 0) }}</h4>
                            <small class="text-muted">ج.م متبقي</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الحملة</h5>
                </div>
                <div class="card-body">
                    <p><strong>منشئ الحملة:</strong></p>
                    <div class="d-flex align-items-center mb-3">
                        @if ($campaign->creator->profile_image)
                            <img src="{{ asset('storage/' . $campaign->creator->profile_image) }}" alt="Creator"
                                class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                        @else
                            <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $campaign->creator->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $campaign->creator->mobile }}</small>
                        </div>
                    </div>

                    <hr>

                    <p><strong>تاريخ الإنشاء:</strong> {{ $campaign->created_at->format('Y/m/d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $campaign->updated_at->format('Y/m/d H:i') }}</p>

                    @if ($campaign->end_date)
                        <p><strong>تاريخ الانتهاء:</strong> {{ $campaign->end_date->format('Y/m/d') }}</p>
                    @endif

                    <p><strong>الحالة:</strong>
                        @if ($campaign->is_active)
                            <span class="badge bg-success">نشطة</span>
                        @else
                            <span class="badge bg-secondary">غير نشطة</span>
                        @endif
                    </p>

                    <p><strong>مميزة:</strong>
                        @if ($campaign->is_priority)
                            <span class="badge bg-warning">نعم</span>
                        @else
                            <span class="badge bg-secondary">لا</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-primary"
                            target="_blank">
                            <i class="bi bi-eye me-2"></i>
                            عرض في الموقع
                        </a>
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>
                            تعديل الحملة
                        </a>
                        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الحملة؟ سيتم حذف جميع التبرعات المرتبطة بها.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>
                                حذف الحملة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
