@extends('layouts.admin')

@section('title', 'إدارة التبرعات - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>إدارة التبرعات</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $donations->total() }}</h4>
                            <p class="mb-0">إجمالي التبرعات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-gift" style="font-size: 2rem;"></i>
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
                            <h4>{{ number_format($donations->sum('amount'), 0) }} ج.م</h4>
                            <p class="mb-0">إجمالي المبلغ</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
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
                            <h4>{{ $donations->where('is_anonymous', true)->count() }}</h4>
                            <p class="mb-0">التبرعات المجهولة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-eye-slash" style="font-size: 2rem;"></i>
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
                            <h4>{{ number_format($donations->avg('amount'), 0) }} ج.م</h4>
                            <p class="mb-0">متوسط التبرع</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-bar-chart" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">قائمة التبرعات</h5>
        </div>
        <div class="card-body">
            @if ($donations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>المتبرع</th>
                                <th>الحملة</th>
                                <th>المبلغ</th>
                                <th>النوع</th>
                                <th>تاريخ التبرع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td>
                                        @if ($donation->is_anonymous)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-eye-slash text-white"></i>
                                                </div>
                                                <div>
                                                    <strong>متبرع مجهول</strong>
                                                    <br>
                                                    <small class="text-muted">مخفي الهوية</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                @if ($donation->user->profile_image)
                                                    <img src="{{ asset('storage/' . $donation->user->profile_image) }}"
                                                        alt="Profile" class="rounded-circle me-3" width="40"
                                                        height="40" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-primary d-flex align-items-center justify-content-center rounded-circle me-3"
                                                        style="width: 40px; height: 40px;">
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
                                        <div class="d-flex align-items-center">
                                            @if ($donation->campaign->images && count($donation->campaign->images) > 0)
                                                <img src="{{ asset('storage/' . $donation->campaign->images[0]) }}"
                                                    alt="Campaign" class="rounded me-3" width="40" height="40"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="bg-primary d-flex align-items-center justify-content-center rounded me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-heart-fill text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ \Illuminate\Support\Str::limit($donation->campaign->title, 30) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $donation->campaign->creator->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success fs-6">{{ number_format($donation->amount, 2) }}
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
                                        <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('campaigns.show', $donation->campaign) }}"
                                                class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if (!$donation->is_anonymous)
                                                <a href="{{ route('admin.users.show', $donation->user) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-person"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $donations->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-gift text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">لا توجد تبرعات</h4>
                </div>
            @endif
        </div>
    </div>
@endsection
