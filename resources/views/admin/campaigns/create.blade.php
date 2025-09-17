@extends('layouts.admin')

@section('title', 'إضافة حملة جديدة - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>إضافة حملة جديدة</h1>
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">بيانات الحملة</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.campaigns.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان الحملة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">وصف الحملة <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Amount -->
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">المبلغ المستهدف (ج.م) <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('target_amount') is-invalid @enderror"
                                id="target_amount" name="target_amount" value="{{ old('target_amount') }}" min="1"
                                step="0.01" required>
                            @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="mb-3">
                            <label for="end_date" class="form-label">تاريخ انتهاء الحملة</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اختياري - اتركه فارغاً إذا كانت الحملة مفتوحة</div>
                        </div>

                        <!-- Creator -->
                        <div class="mb-3">
                            <label for="creator_id" class="form-label">منشئ الحملة <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('creator_id') is-invalid @enderror" id="creator_id"
                                name="creator_id" required>
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('creator_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->mobile }})
                                    </option>
                                @endforeach
                            </select>
                            @error('creator_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">صور الحملة</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images"
                                name="images[]" multiple accept="image/*">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">يمكنك رفع حتى 5 صور (اختياري)</div>
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <label class="form-label">معاينة الصور:</label>
                            <div id="previewContainer" class="d-flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Status Options -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        حملة نشطة
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_priority" name="is_priority"
                                        value="1" {{ old('is_priority') ? 'checked' : '' }}>
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
                                إنشاء الحملة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">نصائح لإنشاء حملة ناجحة</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            اختر عنواناً واضحاً ومؤثراً
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            اكتب وصفاً مفصلاً عن الحالة
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            ارفع صوراً واضحة وذات جودة عالية
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            حدد مبلغاً واقعياً ومناسباً
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            اختر المستخدم المناسب كمنشئ للحملة
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Image Guidelines -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">إرشادات الصور</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-info-circle text-info me-2"></i>
                            الحد الأقصى: 5 صور
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-info-circle text-info me-2"></i>
                            الحجم الأقصى: 2 ميجابايت لكل صورة
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-info-circle text-info me-2"></i>
                            الأنواع المدعومة: JPG, PNG, GIF
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-info-circle text-info me-2"></i>
                            الأبعاد المفضلة: 800x600 بكسل
                        </li>
                    </ul>
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

                    // Check file count limit
                    if (files.length > 5) {
                        alert('يمكنك رفع 5 صور كحد أقصى');
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
                        <small class="d-block text-center mt-1">صورة ${index + 1}</small>
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

            // Set minimum date to tomorrow
            document.getElementById('end_date').min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
        </script>
    @endpush
@endsection
