<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شروط الاستخدام - نبض الحياة</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Mobile Styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }
        
        .mobile-container {
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .mobile-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
        }
        
        .section-title {
            color: #28a745;
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
            background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-right: 4px solid #28a745;
        }
        
        .warning-box {
            background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-right: 4px solid #ffc107;
        }
        
        .danger-box {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-right: 4px solid #dc3545;
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
            border-color: #28a745;
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
            color: #28a745;
        }
        
        .allowed-card {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .forbidden-card {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 2px solid #dc3545;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .contact-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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
                <i class="bi bi-file-text me-2"></i>
                شروط الاستخدام
            </h1>
            <p class="mb-0 opacity-75">نبض الحياة للتبرعات الخيرية</p>
            <small class="opacity-50">آخر تحديث: {{ date('Y-m-d') }}</small>
        </div>

        <!-- Mobile Content -->
        <div class="mobile-content">
            
            <!-- الموافقة على الشروط -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-check-circle"></i>
                    الموافقة على الشروط
                </h2>
                <div class="info-box">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>مهم جداً</strong>
                    </div>
                    <p class="mb-0">
                        باستخدامك لمنصة "نبض الحياة"، فإنك توافق على الالتزام بهذه الشروط والأحكام.
                    </p>
                </div>
            </div>

            <!-- تعريف الخدمة -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-heart"></i>
                    تعريف الخدمة
                </h2>
                <p class="text-muted mb-3">
                    "نبض الحياة" منصة إلكترونية للتبرعات الخيرية تربط المتبرعين بالحملات الخيرية بطريقة آمنة وشفافة.
                </p>
                
                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon bg-success">
                            <i class="bi bi-people"></i>
                        </div>
                        <h6>للمتبرعين</h6>
                        <small class="text-muted">تبرع آمن وسهل</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-info">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <h6>للحملات</h6>
                        <small class="text-muted">منصة لجمع التبرعات</small>
                    </div>
                </div>
            </div>

            <!-- التسجيل والحساب -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-person-plus"></i>
                    التسجيل والحساب
                </h2>
                
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>يجب أن تكون بالغاً (18 سنة أو أكثر)</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>تقديم معلومات صحيحة ودقيقة</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>الحفاظ على سرية كلمة المرور</span>
                </div>
                <div class="list-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>إشعارنا فوراً في حالة اختراق الحساب</span>
                </div>
            </div>

            <!-- قواعد التبرع -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-gift"></i>
                    قواعد التبرع
                </h2>
                
                <div class="allowed-card">
                    <h6 class="text-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        المسموح
                    </h6>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-success"></i>
                        <span>التبرع للحملات المعتمدة</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-success"></i>
                        <span>التبرع المجهول</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-success"></i>
                        <span>إضافة رسائل تشجيعية</span>
                    </div>
                </div>
                
                <div class="forbidden-card">
                    <h6 class="text-danger mb-3">
                        <i class="bi bi-x-circle me-2"></i>
                        غير المسموح
                    </h6>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-x text-danger"></i>
                        <span>التبرع بأموال غير مشروعة</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-x text-danger"></i>
                        <span>إنشاء حملات وهمية</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-x text-danger"></i>
                        <span>استخدام معلومات مزيفة</span>
                    </div>
                </div>
            </div>

            <!-- المدفوعات والاسترداد -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-credit-card"></i>
                    المدفوعات والاسترداد
                </h2>
                
                <div class="warning-box">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        <strong>تنبيه مهم</strong>
                    </div>
                    <p class="mb-0 small">جميع التبرعات نهائية ولا يمكن استردادها إلا في حالات استثنائية</p>
                </div>
                
                <div class="feature-grid">
                    <div class="feature-item">
                        <div class="feature-icon bg-primary">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6>مدفوعات آمنة</h6>
                        <small class="text-muted">حماية عالية</small>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon bg-success">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h6>إيصالات فورية</h6>
                        <small class="text-muted">لكل تبرع</small>
                    </div>
                </div>
            </div>

            <!-- المسؤوليات -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-balance"></i>
                    المسؤوليات
                </h2>
                
                <div class="info-box">
                    <h6 class="text-success mb-2">
                        <i class="bi bi-building me-2"></i>
                        مسؤوليات المنصة
                    </h6>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-success"></i>
                        <span>توفير خدمة آمنة وموثوقة</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-success"></i>
                        <span>حماية بيانات المستخدمين</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h6 class="text-info mb-2">
                        <i class="bi bi-person me-2"></i>
                        مسؤوليات المستخدم
                    </h6>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-info"></i>
                        <span>استخدام المنصة بطريقة قانونية</span>
                    </div>
                    <div class="list-item border-0 p-0">
                        <i class="bi bi-check text-info"></i>
                        <span>تقديم معلومات صحيحة</span>
                    </div>
                </div>
            </div>

            <!-- إنهاء الخدمة -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-x-octagon"></i>
                    إنهاء الخدمة
                </h2>
                <p class="text-muted mb-3">
                    نحتفظ بالحق في إيقاف حسابك في حالة مخالفة الشروط.
                </p>
                <div class="danger-box">
                    <h6 class="text-danger mb-2">أسباب إنهاء الحساب:</h6>
                    <ul class="mb-0 small">
                        <li>مخالفة شروط الاستخدام</li>
                        <li>استخدام معلومات مزيفة</li>
                        <li>محاولة الاحتيال</li>
                        <li>السلوك المسيء</li>
                    </ul>
                </div>
            </div>

            <!-- القانون المطبق -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="bi bi-flag"></i>
                    القانون المطبق
                </h2>
                <p class="text-muted mb-0">
                    تخضع هذه الشروط لقوانين المملكة العربية السعودية.
                </p>
            </div>

            <!-- التواصل معنا -->
            <div class="contact-card">
                <h3 class="h5 mb-3">
                    <i class="bi bi-envelope me-2"></i>
                    التواصل معنا
                </h3>
                <p class="mb-3">لأي استفسارات حول شروط الاستخدام</p>
                <div class="row text-center">
                    <div class="col-6">
                        <i class="bi bi-envelope d-block mb-2"></i>
                        <small>support@nabdhayah.com</small>
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
