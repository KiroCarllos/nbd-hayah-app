@extends('layouts.app')

@section('title', 'سياسة الخصوصية')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-shield-check me-3"></i>
                    سياسة الخصوصية
                </h1>
                <p class="lead text-muted">
                    نحن في نبض الحياة نلتزم بحماية خصوصيتك وبياناتك الشخصية
                </p>
                <div class="text-muted small">
                    آخر تحديث: {{ date('Y-m-d') }}
                </div>
            </div>

            <!-- Content -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    
                    <!-- مقدمة -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            مقدمة
                        </h2>
                        <p class="text-muted lh-lg">
                            تحدد هذه السياسة كيفية جمع واستخدام وحماية المعلومات الشخصية التي تقدمها عند استخدام منصة "نبض الحياة" للتبرعات الخيرية. نحن ملتزمون بحماية خصوصيتك وضمان أمان بياناتك.
                        </p>
                    </section>

                    <!-- المعلومات التي نجمعها -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-collection me-2"></i>
                            المعلومات التي نجمعها
                        </h2>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="text-success mb-3">
                                            <i class="bi bi-person me-2"></i>
                                            المعلومات الشخصية
                                        </h5>
                                        <ul class="list-unstyled text-muted">
                                            <li><i class="bi bi-check text-success me-2"></i>الاسم الكامل</li>
                                            <li><i class="bi bi-check text-success me-2"></i>رقم الهاتف المحمول</li>
                                            <li><i class="bi bi-check text-success me-2"></i>عنوان البريد الإلكتروني</li>
                                            <li><i class="bi bi-check text-success me-2"></i>الصورة الشخصية (اختيارية)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="text-info mb-3">
                                            <i class="bi bi-credit-card me-2"></i>
                                            معلومات المعاملات
                                        </h5>
                                        <ul class="list-unstyled text-muted">
                                            <li><i class="bi bi-check text-info me-2"></i>تفاصيل التبرعات</li>
                                            <li><i class="bi bi-check text-info me-2"></i>معاملات المحفظة</li>
                                            <li><i class="bi bi-check text-info me-2"></i>سجل الدفع</li>
                                            <li><i class="bi bi-check text-info me-2"></i>الحملات المفضلة</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- كيف نستخدم معلوماتك -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-gear me-2"></i>
                            كيف نستخدم معلوماتك
                        </h2>
                        <div class="alert alert-info border-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="bi bi-lightbulb text-info" style="font-size: 2rem;"></i>
                                </div>
                                <div class="col">
                                    <p class="mb-0">
                                        نستخدم معلوماتك الشخصية لتوفير خدماتنا وتحسين تجربتك على المنصة
                                    </p>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                معالجة التبرعات والمعاملات المالية
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                إرسال إشعارات حول حالة التبرعات
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                تحسين خدماتنا وتطوير ميزات جديدة
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                ضمان أمان المنصة ومنع الاحتيال
                            </li>
                        </ul>
                    </section>

                    <!-- حماية البيانات -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-shield-lock me-2"></i>
                            حماية البيانات
                        </h2>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-lock-fill"></i>
                                    </div>
                                    <h5>تشفير البيانات</h5>
                                    <p class="text-muted small">جميع البيانات محمية بتشفير SSL</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-server"></i>
                                    </div>
                                    <h5>خوادم آمنة</h5>
                                    <p class="text-muted small">بيانات محفوظة في خوادم محمية</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-eye-slash"></i>
                                    </div>
                                    <h5>خصوصية تامة</h5>
                                    <p class="text-muted small">إمكانية التبرع المجهول</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- مشاركة المعلومات -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-share me-2"></i>
                            مشاركة المعلومات
                        </h2>
                        <div class="alert alert-warning border-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle text-warning me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>نحن لا نبيع أو نؤجر معلوماتك الشخصية لأطراف ثالثة</strong>
                                    <p class="mb-0 mt-2">قد نشارك معلوماتك فقط في الحالات التالية:</p>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                مع موافقتك الصريحة
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                لمعالجة المدفوعات مع بوابات الدفع المعتمدة
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                عند الطلب من السلطات القانونية المختصة
                            </li>
                        </ul>
                    </section>

                    <!-- حقوقك -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-person-check me-2"></i>
                            حقوقك
                        </h2>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-eye text-primary me-2"></i>
                                        الاطلاع على بياناتك الشخصية
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-pencil text-primary me-2"></i>
                                        تعديل أو تحديث معلوماتك
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-trash text-primary me-2"></i>
                                        طلب حذف حسابك
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-download text-primary me-2"></i>
                                        تحميل نسخة من بياناتك
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-x-circle text-primary me-2"></i>
                                        إيقاف الإشعارات
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-question-circle text-primary me-2"></i>
                                        الاستفسار عن استخدام بياناتك
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- ملفات تعريف الارتباط -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-cookie me-2"></i>
                            ملفات تعريف الارتباط (Cookies)
                        </h2>
                        <p class="text-muted lh-lg">
                            نستخدم ملفات تعريف الارتباط لتحسين تجربتك على الموقع وحفظ تفضيلاتك. يمكنك التحكم في هذه الملفات من خلال إعدادات المتصفح الخاص بك.
                        </p>
                    </section>

                    <!-- التواصل معنا -->
                    <section class="mb-4">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-envelope me-2"></i>
                            التواصل معنا
                        </h2>
                        <div class="alert alert-primary border-0">
                            <p class="mb-3">
                                إذا كان لديك أي استفسارات حول سياسة الخصوصية، يمكنك التواصل معنا:
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-envelope me-2"></i>
                                        <strong>البريد الإلكتروني:</strong> privacy@nabdhayah.com
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-telephone me-2"></i>
                                        <strong>الهاتف:</strong> +966 11 123 4567
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-house me-2"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .list-group-item {
        transition: all 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        padding-right: 1rem;
    }
    
    section {
        scroll-margin-top: 100px;
    }
</style>
@endsection
