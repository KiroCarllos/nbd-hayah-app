<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نبض الحياة - منصة التبرعات')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .progress {
            height: 8px;
        }

        .campaign-card {
            transition: transform 0.2s;
        }

        .campaign-card:hover {
            transform: translateY(-5px);
        }

        .favorite-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .favorite-btn:hover {
            background: rgba(255, 255, 255, 1);
        }

        .favorite-btn.active {
            color: #dc3545;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('logo-text-right.png') }}" alt="نبض الحياة" class="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/campaigns') }}">الحملات</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                @if (auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile"
                                        class="rounded-circle me-2" width="30" height="30">
                                @else
                                    <i class="bi bi-person-circle me-2"></i>
                                @endif
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu" style="z-index: 9999;">
                                @if (auth()->user()->is_admin)
                                    <li><a class="dropdown-item text-primary-custom" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>لوحة التحكم
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="{{ route('wallet.index') }}">المحفظة
                                        (@currency(auth()->user()->wallet_balance))
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('donations.index') }}">تبرعاتي</a></li>
                                <li><a class="dropdown-item" href="{{ route('favorites.index') }}">المفضلة</a></li>
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
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">إنشاء حساب</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pb-4">
        @if (session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="footer-main">
            <div class="container">
                <div class="row g-4">
                    <!-- Logo and Description -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <div class="footer-logo mb-3">
                                <img src="{{ asset('logo-text-bottom.png') }}" alt="نبض الحياة"
                                    style="filter: brightness(0) invert(1); max-height: 60px;">
                            </div>
                            <p class="footer-desc mb-4">
                                منصة التبرعات الخيرية الرائدة في المنطقة، نساعد المحتاجين ونحدث فرقاً حقيقياً في المجتمع
                                من خلال تبرعاتكم الكريمة.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">روابط سريعة</h5>
                            <ul class="footer-links">
                                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                <li><a href="{{ route('campaigns.index') }}">الحملات</a></li>
                                <li><a href="{{ route('favorites.index') }}">المفضلة</a></li>
                                @auth
                                    <li><a href="{{ route('profile.show') }}">الملف الشخصي</a></li>
                                    <li><a href="{{ route('wallet.index') }}">المحفظة</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">الدعم</h5>
                            <ul class="footer-links">
                                <li><a href="#">مركز المساعدة</a></li>
                                <li><a href="#">الأسئلة الشائعة</a></li>
                                <li><a href="#">اتصل بنا</a></li>
                                <li><a href="#">سياسة الخصوصية</a></li>
                                <li><a href="#">الشروط والأحكام</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">تواصل معنا</h5>
                            <div class="footer-contact">
                                <div class="contact-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div>
                                        <strong>العنوان:</strong><br>
                                        القاهرة، مصر
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-telephone-fill"></i>
                                    <div>
                                        <strong>الهاتف:</strong><br>
                                        <a href="tel:+201234567890">+20 123 456 7890</a>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-envelope-fill"></i>
                                    <div>
                                        <strong>البريد الإلكتروني:</strong><br>
                                        <a href="mailto:info@nabdhayah.com">info@nabdhayah.com</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} نبض الحياة. جميع الحقوق محفوظة.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-payment">
                            <span class="me-3">طرق الدفع المتاحة:</span>
                            <i class="bi bi-credit-card-2-front payment-icon"></i>
                            <i class="bi bi-paypal payment-icon"></i>
                            <i class="bi bi-bank payment-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
