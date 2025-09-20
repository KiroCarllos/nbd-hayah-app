@extends('layouts.admin')

@section('title', 'تفاصيل التحديث - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>تفاصيل التحديث</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">الحملات</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.show', $campaign) }}">{{ Str::limit($campaign->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaign-updates.index', $campaign) }}">التحديثات</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($update->title, 30) }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.campaign-updates.edit', [$campaign, $update]) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-2"></i>
                تعديل
            </a>
            <a href="{{ route('admin.campaign-updates.index', $campaign) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right me-2"></i>
                العودة للتحديثات
            </a>
        </div>
    </div>

    <!-- Campaign Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="card-title">{{ $campaign->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($campaign->description, 150) }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-{{ $campaign->is_active ? 'success' : 'danger' }}">
                        {{ $campaign->is_active ? 'نشطة' : 'غير نشطة' }}
                    </span>
                    @if($campaign->is_priority)
                        <span class="badge bg-warning">أولوية</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Update Content -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $update->title }}</h5>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-info">{{ $update->type_name }}</span>
                                @if($update->is_important)
                                    <span class="badge bg-danger">مهم</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="card-text" style="white-space: pre-line;">{{ $update->content }}</p>
                    </div>

                    <!-- Images -->
                    @if($update->images && count($update->images) > 0)
                        <div class="mb-4">
                            <h6 class="mb-3">الصور المرفقة</h6>
                            <div class="row">
                                @foreach($update->images as $index => $image)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <a href="{{ asset('storage/' . $image) }}" class="glightbox" 
                                               data-gallery="update-images" 
                                               data-title="{{ $update->title }}"
                                               data-description="صورة {{ $index + 1 }} من {{ count($update->images) }}">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     class="card-img-top" 
                                                     alt="صورة التحديث {{ $index + 1 }}"
                                                     style="height: 200px; object-fit: cover;">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Update Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">معلومات التحديث</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>النوع:</strong>
                        <span class="badge bg-info ms-2">{{ $update->type_name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>الحالة:</strong>
                        @if($update->is_important)
                            <span class="badge bg-danger ms-2">مهم</span>
                        @else
                            <span class="badge bg-secondary ms-2">عادي</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>أضيف بواسطة:</strong>
                        <div class="mt-1">{{ $update->creator->name }}</div>
                    </div>

                    <div class="mb-3">
                        <strong>تاريخ الإضافة:</strong>
                        <div class="mt-1">{{ $update->created_at->format('Y/m/d h:i A') }}</div>
                    </div>

                    @if($update->updated_at != $update->created_at)
                        <div class="mb-3">
                            <strong>آخر تحديث:</strong>
                            <div class="mt-1">{{ $update->updated_at->format('Y/m/d h:i A') }}</div>
                        </div>
                    @endif

                    @if($update->images && count($update->images) > 0)
                        <div class="mb-3">
                            <strong>عدد الصور:</strong>
                            <span class="badge bg-success ms-2">{{ count($update->images) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">الإجراءات</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.campaign-updates.edit', [$campaign, $update]) }}" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>
                            تعديل التحديث
                        </a>
                        
                        <form method="POST" 
                              action="{{ route('admin.campaign-updates.destroy', [$campaign, $update]) }}"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا التحديث؟ لا يمكن التراجع عن هذا الإجراء.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>
                                حذف التحديث
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
    const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
</script>
@endpush
