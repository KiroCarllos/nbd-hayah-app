@extends('layouts.admin')

@section('title', 'تحديثات الحملة - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>تحديثات الحملة: {{ $campaign->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">الحملات</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.show', $campaign) }}">{{ Str::limit($campaign->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">التحديثات</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.campaign-updates.create', $campaign) }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle me-2"></i>
                إضافة تحديث جديد
            </a>
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right me-2"></i>
                العودة للحملة
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
                    <div class="mb-2">
                        <span class="badge bg-{{ $campaign->is_active ? 'success' : 'danger' }}">
                            {{ $campaign->is_active ? 'نشطة' : 'غير نشطة' }}
                        </span>
                        @if($campaign->is_priority)
                            <span class="badge bg-warning">أولوية</span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <div>المبلغ المحصل: <strong>@currency($campaign->current_amount)</strong></div>
                        <div>المبلغ المستهدف: <strong>@currency($campaign->target_amount)</strong></div>
                        <div>عدد التحديثات: <strong>{{ $updates->total() }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($updates->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">قائمة التحديثات ({{ $updates->total() }} تحديث)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>العنوان</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>أضيف بواسطة</th>
                                <th>تاريخ الإضافة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($updates as $update)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $update->title }}</strong>
                                            @if($update->is_important)
                                                <span class="badge bg-danger ms-2">مهم</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ Str::limit($update->content, 80) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $update->type_name }}</span>
                                    </td>
                                    <td>
                                        @if($update->images && count($update->images) > 0)
                                            <i class="bi bi-image text-success" title="يحتوي على صور"></i>
                                        @endif
                                    </td>
                                    <td>{{ $update->creator->name }}</td>
                                    <td>
                                        <div>{{ $update->created_at->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $update->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.campaign-updates.show', [$campaign, $update]) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.campaign-updates.edit', [$campaign, $update]) }}" 
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.campaign-updates.destroy', [$campaign, $update]) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا التحديث؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($updates->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $updates->links() }}
            </div>
        @endif
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-square-text display-1 text-muted mb-3"></i>
                <h4>لا توجد تحديثات</h4>
                <p class="text-muted">لم يتم إضافة أي تحديثات لهذه الحملة بعد.</p>
                <a href="{{ route('admin.campaign-updates.create', $campaign) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    إضافة أول تحديث
                </a>
            </div>
        </div>
    @endif
@endsection
