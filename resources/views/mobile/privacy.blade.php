<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سياسة الخصوصية - نبض الحياة</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Mobile Styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .mobile-container {
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .mobile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .mobile-content {
            padding: 20px 15px;
            line-height: 1.8;
        }

        .section-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .section-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 1.2rem;
        }

        .section-title i {
            margin-left: 10px;
            font-size: 1.4rem;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-right: 4px solid #667eea;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .feature-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-size: 1.2rem;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item i {
            margin-left: 10px;
            color: #667eea;
        }

        .contact-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .mobile-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .btn-mobile {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-mobile:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @media (max-width: 576px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .mobile-content {
                padding: 15px 10px;
            }

            .section-card {
                padding: 15px;
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="mobile-container">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <h1 class="h3 mb-2">
                <i class="bi bi-shield-check me-2"></i>
                سياسة الخصوصية
            </h1>
            <p class="mb-0 opacity-75">نبض الحياة للتبرعات الخيرية</p>
            <small class="opacity-50">آخر تحديث: {{ date('Y-m-d') }}</small>
        </div>

        <!-- Mobile Content -->
        <div class="mobile-content">

            <!-- مقدمة -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    مقدمة
                </h2>
                <div class="info-box">
                    <p class="mb-0">
                        نحن في نبض الحياة نلتزم بحماية خصوصيتك وبياناتك الشخصية. تحدد هذه السياسة كيفية جمع واستخدام
                        وحماية معلوماتك عند استخدام منصتنا.
                    </p>
                </div>
            </div>

            <!-- المعلومات التي نجمعها -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-collection"></i>
                    المعلومات التي نجمعها
                </h2>

                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon bg-success">
                            <i class="bi bi-person"></i>
                        </div>
                        <h6>المعلومات الشخصية</h6>
                        <small class="text-muted">الاسم، الهاتف، البريد الإلكتروني</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-info">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h6>معلومات المعاملات</h6>
                        <small class="text-muted">التبرعات، المحفظة، المدفوعات</small>
                    </div>
                </div>
            </div>

            <!-- كيف نستخدم معلوماتك -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-gear"></i>
                    كيف نستخدم معلوماتك
                </h2>

                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>معالجة التبرعات والمعاملات المالية</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>إرسال إشعارات حول حالة التبرعات</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>تحسين خدماتنا وتطوير ميزات جديدة</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>ضمان أمان المنصة ومنع الاحتيال</span>
                </div>
            </div>

            <!-- حماية البيانات -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-shield-lock"></i>
                    حماية البيانات
                </h2>

                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon bg-success">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <h6>تشفير البيانات</h6>
                        <small class="text-muted">حماية بتشفير SSL</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-info">
                            <i class="bi bi-server"></i>
                        </div>
                        <h6>خوادم آمنة</h6>
                        <small class="text-muted">بيانات محمية</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-warning">
                            <i class="bi bi-eye-slash"></i>
                        </div>
                        <h6>خصوصية تامة</h6>
                        <small class="text-muted">تبرع مجهول</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-primary">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6>أمان عالي</h6>
                        <small class="text-muted">حماية شاملة</small>
                    </div>
                </div>
            </div>

            <!-- مشاركة المعلومات -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-share"></i>
                    مشاركة المعلومات
                </h2>

                <div class="info-box">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        <strong>نحن لا نبيع أو نؤجر معلوماتك الشخصية</strong>
                    </div>
                    <p class="mb-0 small">قد نشارك معلوماتك فقط مع موافقتك أو للمعالجة القانونية للمدفوعات</p>
                </div>
            </div>

            <!-- حقوقك -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-person-check"></i>
                    حقوقك
                </h2>

                <div class="list-item">
                    <i class="bi bi-eye text-primary"></i>
                    <span>الاطلاع على بياناتك الشخصية</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-pencil text-primary"></i>
                    <span>تعديل أو تحديث معلوماتك</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-trash text-primary"></i>
                    <span>طلب حذف حسابك</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-download text-primary"></i>
                    <span>تحميل نسخة من بياناتك</span>
                </div>
            </div>

            <!-- ملفات تعريف الارتباط -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-cookie"></i>
                    ملفات تعريف الارتباط
                </h2>
                <p class="text-muted mb-0">
                    نستخدم ملفات تعريف الارتباط لتحسين تجربتك وحفظ تفضيلاتك. يمكنك التحكم فيها من إعدادات المتصفح.
                </p>
            </div>

            <!-- التواصل معنا -->
            <div class="contact-card">
                <h3 class="h5 mb-3">
                    <i class="bi bi-envelope me-2"></i>
                    التواصل معنا
                </h3>
                <p class="mb-3">لأي استفسارات حول سياسة الخصوصية</p>
                <div class="row text-center">
                    <div class="col-6">
                        <i class="bi bi-envelope d-block mb-2"></i>
                        <small>privacy@nabdhayah.com</small>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-telephone d-block mb-2"></i>
                        <small>+966 11 123 4567</small>
                    </div>
                </div>
            </div>

        </div>

        <!-- Mobile Footer -->
        <div class="mobile-footer">
            <p class="text-muted small mb-3">
                © {{ date('Y') }} نبض الحياة. جميع الحقوق محفوظة.
            </p>
            <a href="javascript:history.back()" class="btn-mobile">
                <i class="bi bi-arrow-right me-2"></i>
                العودة
            </a>
            <div class="mt-3">
                <small class="text-muted">مصمم خصيصاً للموبايل أبليكيشن</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
