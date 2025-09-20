@extends('layouts.admin')

@section('title', 'إضافة تحديث جديد - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>إضافة تحديث جديد</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">الحملات</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.show', $campaign) }}">{{ Str::limit($campaign->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaign-updates.index', $campaign) }}">التحديثات</a></li>
                    <li class="breadcrumb-item active">إضافة تحديث</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.campaign-updates.index', $campaign) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            العودة للتحديثات
        </a>
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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">بيانات التحديث</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.campaign-updates.store', $campaign) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان التحديث <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-3">
                            <label for="content" class="form-label">محتوى التحديث <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع التحديث <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">اختر نوع التحديث</option>
                                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>عام</option>
                                <option value="medical" {{ old('type') == 'medical' ? 'selected' : '' }}>طبي</option>
                                <option value="financial" {{ old('type') == 'financial' ? 'selected' : '' }}>مالي</option>
                                <option value="progress" {{ old('type') == 'progress' ? 'selected' : '' }}>تقدم الحالة</option>
                                <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>عاجل</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Important -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_important" name="is_important" 
                                       value="1" {{ old('is_important') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_important">
                                    تحديث مهم
                                </label>
                            </div>
                            <small class="form-text text-muted">التحديثات المهمة تظهر بشكل مميز للمستخدمين</small>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">صور التحديث (اختيارية)</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">يمكنك رفع حتى 5 صور. الحد الأقصى لحجم كل صورة 2 ميجابايت.</small>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                إضافة التحديث
                            </button>
                            <a href="{{ route('admin.campaign-updates.index', $campaign) }}" class="btn btn-secondary">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">نصائح</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            اكتب عنوان واضح ومختصر للتحديث
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            اختر النوع المناسب للتحديث لتسهيل التصنيف
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            استخدم "تحديث مهم" للأخبار العاجلة فقط
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            أضف صور توضيحية عند الحاجة
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">أنواع التحديثات</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-info me-2">عام</span>
                        <small>تحديثات عامة حول الحملة</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success me-2">طبي</span>
                        <small>تحديثات طبية حول الحالة</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-primary me-2">مالي</span>
                        <small>تحديثات مالية ومصروفات</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-warning me-2">تقدم الحالة</span>
                        <small>تطورات في حالة المريض</small>
                    </div>
                    <div class="mb-0">
                        <span class="badge bg-danger me-2">عاجل</span>
                        <small>تحديثات عاجلة ومهمة</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
