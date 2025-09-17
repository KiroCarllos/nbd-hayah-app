@extends('layouts.admin')

@section('title', 'التقارير والإحصائيات المتقدمة')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>
            التقارير والإحصائيات المتقدمة
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i>
                تصدير البيانات
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item"
                        href="{{ route('admin.reports.export', ['type' => 'donations', 'format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>التبرعات (CSV)
                    </a></li>
                <li><a class="dropdown-item"
                        href="{{ route('admin.reports.export', ['type' => 'campaigns', 'format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>الحملات (CSV)
                    </a></li>
                <li><a class="dropdown-item"
                        href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>المستخدمين (CSV)
                    </a></li>
            </ul>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الحملات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCampaigns) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                إجمالي التبرعات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalAmount, 2) }} ج.م
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-donate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                عدد المستخدمين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalUsers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                رصيد المحافظ
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalWalletBalance, 2) }}
                                ج.م</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Monthly Donations Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">التبرعات الشهرية (آخر 12 شهر)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyDonationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">حالة الحملات</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="campaignStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Donations Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">التبرعات اليومية (آخر 30 يوم)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="dailyDonationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Campaigns and Donors -->
    <div class="row">
        <!-- Top Campaigns -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">أفضل الحملات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>الحملة</th>
                                    <th>المنشئ</th>
                                    <th>التبرعات</th>
                                    <th>المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCampaigns as $campaign)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.reports.campaign-details', $campaign->id) }}"
                                                class="text-decoration-none">
                                                {{ Str::limit($campaign->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $campaign->creator->name ?? 'غير محدد' }}</td>
                                        <td>{{ $campaign->donations_count }}</td>
                                        <td>{{ number_format($campaign->current_amount, 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Donors -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">أفضل المتبرعين</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>المتبرع</th>
                                    <th>عدد التبرعات</th>
                                    <th>إجمالي التبرعات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topDonors as $donor)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.reports.user-details', $donor->id) }}"
                                                class="text-decoration-none">
                                                {{ $donor->name }}
                                            </a>
                                        </td>
                                        <td>{{ $donor->donations_count }}</td>
                                        <td>{{ number_format($donor->total_donated, 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anonymity Stats and Payment Methods -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات الخصوصية</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="anonymityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet Transactions -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معاملات المحفظة</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>نوع المعاملة</th>
                                    <th>عدد المعاملات</th>
                                    <th>إجمالي المبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($walletTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <i class="{{ $transaction->icon }} {{ $transaction->color }} me-2"></i>
                                            {{ $transaction->type_ar }}
                                        </td>
                                        <td>{{ number_format($transaction->count) }}</td>
                                        <td>{{ number_format($transaction->total, 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Monthly Donations Chart
        const monthlyCtx = document.getElementById('monthlyDonationsChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $monthlyDonations->map(function ($item) {
                        return sprintf('%04d-%02d', $item->year, $item->month);
                    }),
                ) !!},
                datasets: [{
                    label: 'عدد التبرعات',
                    data: {!! json_encode($monthlyDonations->pluck('count')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    yAxisID: 'y'
                }, {
                    label: 'إجمالي المبلغ (ج.م)',
                    data: {!! json_encode($monthlyDonations->pluck('total')) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        // Campaign Status Chart
        const statusCtx = document.getElementById('campaignStatusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['نشطة', 'غير نشطة', 'مكتملة', 'مميزة'],
                datasets: [{
                    data: [
                        {{ $campaignStats['active'] }},
                        {{ $campaignStats['inactive'] }},
                        {{ $campaignStats['completed'] }},
                        {{ $campaignStats['priority'] }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#17a2b8',
                        '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Daily Donations Chart
        const dailyCtx = document.getElementById('dailyDonationsChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dailyDonations->pluck('date')) !!},
                datasets: [{
                    label: 'عدد التبرعات',
                    data: {!! json_encode($dailyDonations->pluck('count')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Anonymity Chart
        const anonymityCtx = document.getElementById('anonymityChart').getContext('2d');
        const anonymityChart = new Chart(anonymityCtx, {
            type: 'pie',
            data: {
                labels: ['تبرعات علنية', 'تبرعات مجهولة'],
                datasets: [{
                    data: [
                        {{ $anonymityStats['public'] }},
                        {{ $anonymityStats['anonymous'] }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
