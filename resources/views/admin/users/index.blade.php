@extends('layouts.admin')

@section('title', 'إدارة المستخدمين - لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>إدارة المستخدمين</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $users->total() }}</h4>
                            <p class="mb-0">إجمالي المستخدمين</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
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
                            <h4>{{ $users->where('is_admin', true)->count() }}</h4>
                            <p class="mb-0">المديرين</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
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
                            <h4>{{ number_format($users->sum('wallet_balance'), 0) }} ج.م</h4>
                            <p class="mb-0">إجمالي الأرصدة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-wallet2" style="font-size: 2rem;"></i>
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
                            <h4>{{ $users->sum('donations_count') }}</h4>
                            <p class="mb-0">إجمالي التبرعات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">قائمة المستخدمين</h5>
        </div>
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الهاتف</th>
                                <th>رصيد المحفظة</th>
                                <th>التبرعات</th>
                                <th>المعاملات</th>
                                <th>النوع</th>
                                <th>تاريخ التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                                                    class="rounded-circle me-3" width="40" height="40"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center rounded-circle me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ number_format($user->wallet_balance, 2) }}
                                            ج.م</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $user->donations_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $user->wallet_transactions_count }}</span>
                                    </td>
                                    <td>
                                        @if ($user->is_admin)
                                            <span class="badge bg-danger">مدير</span>
                                        @else
                                            <span class="badge bg-secondary">مستخدم</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if (!$user->is_admin && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">لا يوجد مستخدمين</h4>
                </div>
            @endif
        </div>
    </div>
@endsection
