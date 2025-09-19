<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نبض الحياة - منصة التبرعات')</title>



    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- GLightbox CSS - Beautiful Image Lightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

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
            right: 0px;
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
                                        (@currency(auth()->user()?->wallet_balance))
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
                                <li><a href="{{ route('privacy') }}">سياسة الخصوصية</a></li>
                                <li><a href="{{ route('terms') }}">الشروط والأحكام</a></li>
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

    <!-- GLightbox JS - Beautiful Image Lightbox -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <!-- Global Lightbox Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize GLightbox for all images
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: false,
                plyr: {
                    css: 'https://cdn.plyr.io/3.6.8/plyr.css',
                    js: 'https://cdn.plyr.io/3.6.8/plyr.js',
                    config: {
                        ratio: '16:9',
                        youtube: {
                            noCookie: true,
                            rel: 0,
                            showinfo: 0,
                            iv_load_policy: 3
                        },
                        vimeo: {
                            byline: false,
                            portrait: false,
                            title: false,
                            speed: true,
                            transparent: false
                        }
                    }
                },
                skin: 'clean',
                closeButton: true,
                closeOnOutsideClick: true,
                openEffect: 'zoom',
                closeEffect: 'zoom',
                slideEffect: 'slide',
                moreText: 'عرض المزيد',
                moreLength: 60,
                cssEfects: {
                    fade: {
                        in: 'fadeIn',
                        out: 'fadeOut'
                    },
                    zoom: {
                        in: 'zoomIn',
                        out: 'zoomOut'
                    },
                    slide: {
                        in: 'slideInRight',
                        out: 'slideOutLeft'
                    },
                    slideBack: {
                        in: 'slideInLeft',
                        out: 'slideOutRight'
                    },
                    none: {
                        in: 'none',
                        out: 'none'
                    }
                },
                svg: {
                    close: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
                    next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9,18 15,12 9,6"></polyline></svg>',
                    prev: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15,18 9,12 15,6"></polyline></svg>'
                }
            });

            // Add custom styles for better Arabic support
            const style = document.createElement('style');
            style.textContent = `
                /* Enhanced GLightbox Styles for Arabic Support */
                .glightbox-clean .gslide-description {
                    direction: rtl;
                    text-align: right;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .glightbox-clean .gslide-title {
                    direction: rtl;
                    text-align: right;
                    font-weight: 600;
                    font-size: 1.2rem;
                    margin-bottom: 0.5rem;
                }

                .glightbox-clean .gnext,
                .glightbox-clean .gprev {
                    background: rgba(0,0,0,0.7);
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }

                .glightbox-clean .gnext:hover,
                .glightbox-clean .gprev:hover {
                    background: rgba(255,77,87,0.9);
                    transform: scale(1.1);
                }

                .glightbox-clean .gclose {
                    background: rgba(255,77,87,0.9);
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }

                .glightbox-clean .gclose:hover {
                    background: rgba(220,38,38,0.9);
                    transform: scale(1.1);
                }

                .glightbox-clean .gslide-media {
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                }

                .glightbox-clean .goverlay {
                    background: rgba(0,0,0,0.9);
                    backdrop-filter: blur(10px);
                }

                /* Loading animation */
                .glightbox-clean .gloader {
                    border: 3px solid rgba(255,255,255,0.3);
                    border-top: 3px solid #ff4d57;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Counter styles */
                .glightbox-clean .gslide-number {
                    background: rgba(255,77,87,0.9);
                    color: white;
                    padding: 5px 12px;
                    border-radius: 20px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    position: absolute;
                    top: 20px;
                    left: 20px;
                    z-index: 999;
                }
            `;
            document.head.appendChild(style);
        });
    </script>

    <!-- Quick Donation Floating Button -->
    @auth
        <div id="quickDonateBtn" class="quick-donate-btn">
            <i class="bi bi-lightning-fill"></i>
        </div>

        <!-- Quick Donation Modal -->
        <div class="modal fade" id="quickDonateModal" tabindex="-1" aria-labelledby="quickDonateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="quickDonateModalLabel">
                            <i class="bi bi-lightning-fill me-2"></i>
                            تبرع سريع
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="quickDonateForm">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">اختر مبلغ التبرع:</label>
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="50">50 ج.م</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="100">100 ج.م</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="200">200 ج.م</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="500">500 ج.م</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="1000">1000 ج.م</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary w-100 amount-btn"
                                            data-amount="10000">10000 ج.م</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="customAmount" class="form-label">أو أدخل مبلغاً مخصصاً:</label>
                                    <input type="number" class="form-control" id="customAmount" name="amount"
                                        placeholder="أدخل المبلغ" min="1" step="0.01">
                                    <div id="amountFeedback" class="invalid-feedback"></div>
                                    <div class="form-text">
                                        الحد الأدنى: 1 ج.م
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isAnonymous"
                                        name="is_anonymous">
                                    <label class="form-check-label" for="isAnonymous">
                                        تبرع مجهول
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="donationMessage" class="form-label">رسالة (اختياري):</label>
                                <textarea class="form-control" id="donationMessage" name="message" rows="3"
                                    placeholder="اكتب رسالة تشجيعية..."></textarea>
                            </div>

                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-wallet2 me-2"></i>
                                    رصيدك الحالي: <strong>{{ number_format(auth()->user()->wallet_balance, 2) }}
                                        ج.م</strong>
                                </div>
                                <a href="{{ route('wallet.charge') }}" class="btn btn-sm btn-outline-primary"
                                    target="_blank">
                                    <i class="bi bi-plus-circle me-1"></i>شحن المحفظة
                                </a>
                            </div>

                            <div id="insufficientBalanceAlert" class="alert alert-warning d-none">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>رصيد غير كافي!</strong>
                                        <span id="balanceMessage"></span>
                                    </div>
                                    <a href="{{ route('wallet.charge') }}" class="btn btn-sm btn-warning"
                                        target="_blank">
                                        <i class="bi bi-lightning-fill me-1"></i>شحن فوري
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-primary" id="submitQuickDonate">
                            <i class="bi bi-heart-fill me-2"></i>
                            تبرع الآن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <style>
        .quick-donate-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #31a354, #fe4d57);
            auth()->user()?->wallet_balance border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
            animation: pulse 2s infinite;
        }

        .quick-donate-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 123, 255, 0.6);
        }

        .quick-donate-btn i {
            color: white;
            font-size: 24px;
            animation: lightning 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
            }

            50% {
                box-shadow: 0 4px 20px rgba(0, 123, 255, 0.8);
            }

            100% {
                box-shadow: 0 4px 20px rgba(0, 123, 255, 0.4);
            }
        }

        @keyframes lightning {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(5deg);
            }
        }

        .amount-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
            padding-top: 0;
        }

        .amount-btn.disabled {
            cursor: not-allowed !important;
            position: relative;
        }

        .amount-btn.disabled::after {
            content: '🔒';
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 12px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quick donate button click
            document.getElementById('quickDonateBtn').addEventListener('click', function() {
                new bootstrap.Modal(document.getElementById('quickDonateModal')).show();
            });

            // Amount button selection
            document.querySelectorAll('.amount-btn').forEach(btn => {
                const amount = parseFloat(btn.dataset.amount);
                const currentBalance = {{ auth()->user()?->wallet_balance }};

                // Disable button if amount exceeds balance
                if (amount > currentBalance) {
                    btn.classList.add('disabled');
                    btn.style.opacity = '0.5';
                    btn.title =
                        `رصيد غير كافي. تحتاج ${amount.toLocaleString()} ج.م ولديك ${currentBalance.toLocaleString()} ج.م`;
                }

                btn.addEventListener('click', function() {
                    // Check if button is disabled
                    if (this.classList.contains('disabled')) {
                        alert(
                            `رصيد المحفظة غير كافي!\nالمبلغ المطلوب: ${amount.toLocaleString()} ج.م\nرصيدك الحالي: ${currentBalance.toLocaleString()} ج.م\n\nيرجى شحن المحفظة أولاً`
                        );
                        return;
                    }

                    // Remove active class from all buttons
                    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove(
                        'active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    // Set the amount in custom input
                    document.getElementById('customAmount').value = this.dataset.amount;
                });
            });

            // Custom amount input
            document.getElementById('customAmount').addEventListener('input', function() {
                // Remove active class from all amount buttons when typing custom amount
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));

                const amount = parseFloat(this.value);
                const currentBalance = {{ auth()->user()?->wallet_balance }};
                const submitBtn = document.getElementById('submitQuickDonate');

                // Real-time validation
                const feedback = document.getElementById('amountFeedback');

                const balanceAlert = document.getElementById('insufficientBalanceAlert');
                const balanceMessage = document.getElementById('balanceMessage');

                if (amount && amount > 0) {
                    if (amount > currentBalance) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                        feedback.textContent =
                            `رصيد المحفظة غير كافي. رصيدك الحالي: ${currentBalance.toLocaleString()} ج.م`;
                        balanceMessage.textContent =
                            ` تحتاج ${(amount - currentBalance).toLocaleString()} ج.م إضافية`;
                        balanceAlert.classList.remove('d-none');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<i class="bi bi-exclamation-triangle me-2"></i>رصيد غير كافي';
                    } else if (amount > 100000) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                        feedback.textContent = 'الحد الأقصى للتبرع هو 100,000 ج.م';
                        balanceAlert.classList.add('d-none');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML =
                            '<i class="bi bi-exclamation-triangle me-2"></i>مبلغ كبير جداً';
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        feedback.textContent = '';
                        balanceAlert.classList.add('d-none');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>تبرع الآن';
                    }
                } else {
                    this.classList.remove('is-invalid', 'is-valid');
                    feedback.textContent = '';
                    balanceAlert.classList.add('d-none');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>تبرع الآن';
                }
            });

            // Submit quick donation
            document.getElementById('submitQuickDonate').addEventListener('click', function() {
                const form = document.getElementById('quickDonateForm');
                const formData = new FormData(form);
                const submitBtn = this;
                const amountInput = document.getElementById('customAmount');
                const amount = parseFloat(amountInput.value);
                const currentBalance = {{ auth()->user()?->wallet_balance }};

                // Validate amount
                if (!amount || amount <= 0) {
                    alert('يرجى إدخال مبلغ صحيح للتبرع');
                    return;
                }

                if (amount > 100000) {
                    alert('الحد الأقصى للتبرع هو 100,000 ج.م');
                    return;
                }

                // Check wallet balance
                if (amount > currentBalance) {
                    alert(
                        `رصيد المحفظة غير كافي!\nالمبلغ المطلوب: ${amount.toLocaleString()} ج.م\nرصيدك الحالي: ${currentBalance.toLocaleString()} ج.م\n\nيرجى شحن المحفظة أولاً`
                    );
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري التبرع...';

                fetch('{{ route('quick-donate.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert(data.message);
                            // Close modal
                            bootstrap.Modal.getInstance(document.getElementById('quickDonateModal'))
                                .hide();
                            // Reset form
                            form.reset();
                            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove(
                                'active'));
                            // Update wallet balance if displayed
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء التبرع. يرجى المحاولة مرة أخرى.');
                    })
                    .finally(() => {
                        // Re-enable button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>تبرع الآن';
                    });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
