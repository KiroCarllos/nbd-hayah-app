@extends('layouts.admin')

@section('title', 'تعديل الحملة - لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>تعديل الحملة: {{ $campaign->title }}</h1>
    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right me-2"></i>
        العودة للقائمة
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل بيانات الحملة</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان الحملة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $campaign->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف الحملة <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $campaign->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Target Amount -->
                    <div class="mb-3">
                        <label for="target_amount" class="form-label">المبلغ المستهدف (ر.س) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('target_amount') is-invalid @enderror" id="target_amount" name="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}" min="1" step="0.01" required>
                        @error('target_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="mb-3">
                        <label for="end_date" class="form-label">تاريخ انتهاء الحملة</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">اختياري - اتركه فارغاً إذا كانت الحملة مفتوحة</div>
                    </div>

                    <!-- Creator -->
                    <div class="mb-3">
                        <label for="creator_id" class="form-label">منشئ الحملة <span class="text-danger">*</span></label>
                        <select class="form-select @error('creator_id') is-invalid @enderror" id="creator_id" name="creator_id" required>
                            <option value="">اختر المستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('creator_id', $campaign->creator_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->mobile }})
                                </option>
                            @endforeach
                        </select>
                        @error('creator_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Current Images -->
                    @if($campaign->images && count($campaign->images) > 0)
                    <div class="mb-3">
                        <label class="form-label">الصور الحالية:</label>
                        <div class="row">
                            @foreach($campaign->images as $index => $image)
                            <div class="col-md-3 mb-2">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                                    <div class="form-check position-absolute top-0 end-0 m-1">
                                        <input type="checkbox" class="form-check-input bg-danger" name="remove_images[]" value="{{ $image }}" id="remove_{{ $index }}">
                                        <label class="form-check-label text-white" for="remove_{{ $index }}">
                                            <i class="bi bi-trash"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">حدد الصور التي تريد حذفها</small>
                    </div>
                    @endif

                    <!-- New Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label">إضافة صور جديدة</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">يمكنك رفع صور إضافية (الحد الأقصى 5 صور إجمالي)</div>
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">معاينة الصور الجديدة:</label>
                        <div id="previewContainer" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Status Options -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    حملة نشطة
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_priority" name="is_priority" value="1" {{ old('is_priority', $campaign->is_priority) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_priority">
                                    حملة مميزة (تظهر في السلايدر)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Campaign Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">إحصائيات الحملة</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success">{{ number_format($campaign->current_amount, 0) }} ر.س</h4>
                        <small class="text-muted">المبلغ المحصل</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-primary">{{ $campaign->donations()->count() }}</h4>
                        <small class="text-muted">عدد التبرعات</small>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: {{ $campaign->progress_percentage }}%" 
                         aria-valuenow="{{ $campaign->progress_percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}% مكتمل</small>
            </div>
        </div>

        <!-- Campaign Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">معلومات الحملة</h6>
            </div>
            <div class="card-body">
                <p><strong>تاريخ الإنشاء:</strong> {{ $campaign->created_at->format('Y/m/d') }}</p>
                <p><strong>آخر تحديث:</strong> {{ $campaign->updated_at->format('Y/m/d H:i') }}</p>
                <p><strong>الحالة:</strong> 
                    @if($campaign->is_active)
                        <span class="badge bg-success">نشطة</span>
                    @else
                        <span class="badge bg-secondary">غير نشطة</span>
                    @endif
                </p>
                <p><strong>مميزة:</strong> 
                    @if($campaign->is_priority)
                        <span class="badge bg-warning">نعم</span>
                    @else
                        <span class="badge bg-secondary">لا</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (files.length > 0) {
        imagePreview.style.display = 'block';
        
        // Check total image count (current + new)
        const currentImageCount = {{ count($campaign->images ?? []) }};
        const totalImages = currentImageCount + files.length;
        
        if (totalImages > 5) {
            alert('يمكنك رفع 5 صور كحد أقصى إجمالي');
            e.target.value = '';
            imagePreview.style.display = 'none';
            return;
        }
        
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        <small class="d-block text-center mt-1">صورة جديدة ${index + 1}</small>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        imagePreview.style.display = 'none';
    }
});
</script>
@endpush
@endsection
