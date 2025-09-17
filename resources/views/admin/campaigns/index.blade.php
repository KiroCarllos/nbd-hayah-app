@extends('layouts.admin')

@section('title', 'إدارة الحملات - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>إدارة الحملات</h1>
        <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            إضافة حملة جديدة
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $campaigns->total() }}</h4>
                            <p class="mb-0">إجمالي الحملات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $campaigns->where('is_active', true)->count() }}</h4>
                            <p class="mb-0">الحملات النشطة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $campaigns->where('is_priority', true)->count() }}</h4>
                            <p class="mb-0">الحملات المميزة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-star-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($campaigns->sum('current_amount'), 0) }} ج.م</h4>
                            <p class="mb-0">إجمالي التبرعات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">قائمة الحملات</h5>
        </div>
        <div class="card-body">
            @if ($campaigns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الحملة</th>
                                <th>المنشئ</th>
                                <th>المبلغ المستهدف</th>
                                <th>المبلغ المحصل</th>
                                <th>التقدم</th>
                                <th>التبرعات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($campaign->images && count($campaign->images) > 0)
                                                <a href="{{ asset('storage/' . $campaign->images[0]) }}" class="glightbox"
                                                    data-gallery="admin-campaigns" data-title="{{ $campaign->title }}"
                                                    data-description="{{ Str::limit($campaign->description, 200) }}">
                                                    <img src="{{ asset('storage/' . $campaign->images[0]) }}" alt="Campaign"
                                                        class="rounded me-3" width="50" height="50"
                                                        style="object-fit: cover; cursor: pointer;">
                                                </a>
                                            @else
                                                <div class="bg-primary d-flex align-items-center justify-content-center rounded me-3"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="bi bi-heart-fill text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ \Illuminate\Support\Str::limit($campaign->title, 40) }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $campaign->created_at->format('Y/m/d') }}</small>
                                                @if ($campaign->is_priority)
                                                    <span class="badge bg-warning ms-1">مميزة</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($campaign->creator->profile_image)
                                                <img src="{{ asset('storage/' . $campaign->creator->profile_image) }}"
                                                    alt="Creator" class="rounded-circle me-2" width="30"
                                                    height="30">
                                            @else
                                                <i class="bi bi-person-circle me-2"></i>
                                            @endif
                                            {{ $campaign->creator->name }}
                                        </div>
                                    </td>
                                    <td>{{ number_format($campaign->target_amount, 2) }} ج.م</td>
                                    <td>{{ number_format($campaign->current_amount, 2) }} ج.م</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $campaign->progress_percentage }}%"
                                                aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small
                                            class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $campaign->donations_count }}</span>
                                    </td>
                                    <td>
                                        @if ($campaign->is_active)
                                            <span class="badge bg-success">نشطة</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشطة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.campaigns.show', $campaign) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الحملة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
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

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $campaigns->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">لا توجد حملات</h4>
                    <p class="text-muted">ابدأ بإنشاء أول حملة خيرية</p>
                    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        إضافة حملة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
