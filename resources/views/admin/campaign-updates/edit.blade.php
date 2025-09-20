@extends('layouts.admin')

@section('title', 'تعديل التحديث - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>تعديل التحديث: {{ $update->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">الحملات</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.show', $campaign) }}">{{ Str::limit($campaign->title, 30) }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaign-updates.index', $campaign) }}">التحديثات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.campaign-updates.show', [$campaign, $update]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            العودة للتحديث
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
                    <h5 class="mb-0">تعديل بيانات التحديث</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.campaign-updates.update', [$campaign, $update]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان التحديث <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $update->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-3">
                            <label for="content" class="form-label">محتوى التحديث <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" required>{{ old('content', $update->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">نوع التحديث <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">اختر نوع التحديث</option>
                                <option value="general" {{ old('type', $update->type) == 'general' ? 'selected' : '' }}>عام</option>
                                <option value="medical" {{ old('type', $update->type) == 'medical' ? 'selected' : '' }}>طبي</option>
                                <option value="financial" {{ old('type', $update->type) == 'financial' ? 'selected' : '' }}>مالي</option>
                                <option value="progress" {{ old('type', $update->type) == 'progress' ? 'selected' : '' }}>تقدم الحالة</option>
                                <option value="urgent" {{ old('type', $update->type) == 'urgent' ? 'selected' : '' }}>عاجل</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Important -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_important" name="is_important" 
                                       value="1" {{ old('is_important', $update->is_important) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_important">
                                    تحديث مهم
                                </label>
                            </div>
                            <small class="form-text text-muted">التحديثات المهمة تظهر بشكل مميز للمستخدمين</small>
                        </div>

                        <!-- Current Images -->
                        @if($update->images && count($update->images) > 0)
                            <div class="mb-3">
                                <label class="form-label">الصور الحالية</label>
                                <div class="row">
                                    @foreach($update->images as $index => $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     class="card-img-top" 
                                                     alt="صورة {{ $index + 1 }}"
                                                     style="height: 100px; object-fit: cover;">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="remove_images[]" value="{{ $image }}" 
                                                               id="remove_{{ $index }}">
                                                        <label class="form-check-label small" for="remove_{{ $index }}">
                                                            حذف هذه الصورة
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">إضافة صور جديدة (اختيارية)</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                   id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">يمكنك رفع حتى 5 صور إجمالية. الحد الأقصى لحجم كل صورة 2 ميجابايت.</small>
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
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('admin.campaign-updates.show', [$campaign, $update]) }}" class="btn btn-secondary">
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
                    <h6 class="mb-0">معلومات التحديث</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>النوع الحالي:</strong>
                        <span class="badge bg-info ms-2">{{ $update->type_name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>الحالة الحالية:</strong>
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

                    @if($update->images && count($update->images) > 0)
                        <div class="mb-3">
                            <strong>عدد الصور الحالية:</strong>
                            <span class="badge bg-success ms-2">{{ count($update->images) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">نصائح</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            يمكنك حذف الصور الحالية وإضافة صور جديدة
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            تأكد من اختيار النوع المناسب للتحديث
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            استخدم "تحديث مهم" بحذر
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
