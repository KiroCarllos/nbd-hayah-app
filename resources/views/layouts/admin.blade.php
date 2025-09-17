<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم - نبض الحياة')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(-5px);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .navbar-admin {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('logo-text-bottom.png') }}" alt="نبض الحياة" class="logo-large mb-2"
                            style="filter: brightness(0) invert(1);">
                        <div>
                            <small class="text-white-50">لوحة التحكم</small>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                الرئيسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}"
                                href="{{ route('admin.campaigns.index') }}">
                                <i class="bi bi-heart-fill me-2"></i>
                                إدارة الحملات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i>
                                إدارة المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}"
                                href="{{ route('admin.donations.index') }}">
                                <i class="bi bi-gift me-2"></i>
                                إدارة التبرعات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}"
                                href="{{ route('admin.transactions.index') }}">
                                <i class="bi bi-receipt me-2"></i>
                                المعاملات المالية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"
                                href="{{ route('admin.sliders.index') }}">
                                <i class="bi bi-images me-2"></i>
                                إدارة السلايدر
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                                href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-bar-chart me-2"></i>
                                التقارير
                            </a>
                        </li>

                        <hr class="text-white-50">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="bi bi-globe me-2"></i>
                                عرض الموقع
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>
                                الملف الشخصي
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-admin mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                            data-bs-target=".sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    role="button" data-bs-toggle="dropdown">
                                    @if (auth()->user()->profile_image)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                                            alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                    @else
                                        <i class="bi bi-person-circle me-2"></i>
                                    @endif
                                    {{ auth()->user()->name }}
                                    <span class="badge bg-success ms-2">مدير</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">الملف الشخصي</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">عرض
                                            الموقع</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page content -->
                <div class="py-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
