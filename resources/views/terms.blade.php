@extends('layouts.app')

@section('title', 'شروط الاستخدام')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-file-text me-3"></i>
                    شروط الاستخدام
                </h1>
                <p class="lead text-muted">
                    الشروط والأحكام الخاصة باستخدام منصة نبض الحياة للتبرعات الخيرية
                </p>
                <div class="text-muted small">
                    آخر تحديث: {{ date('Y-m-d') }}
                </div>
            </div>

            <!-- Content -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    
                    <!-- الموافقة على الشروط -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-check-circle me-2"></i>
                            الموافقة على الشروط
                        </h2>
                        <div class="alert alert-info border-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-info me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <p class="mb-0">
                                        باستخدامك لمنصة "نبض الحياة"، فإنك توافق على الالتزام بهذه الشروط والأحكام. 
                                        إذا كنت لا توافق على أي من هذه الشروط، يرجى عدم استخدام المنصة.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- تعريف الخدمة -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-heart me-2"></i>
                            تعريف الخدمة
                        </h2>
                        <p class="text-muted lh-lg mb-4">
                            "نبض الحياة" هي منصة إلكترونية للتبرعات الخيرية تهدف إلى ربط المتبرعين بالحملات الخيرية المختلفة وتسهيل عملية التبرع بطريقة آمنة وشفافة.
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people text-success mb-3" style="font-size: 2rem;"></i>
                                        <h5>للمتبرعين</h5>
                                        <p class="text-muted small">إمكانية التبرع للحملات المختلفة بطريقة آمنة</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-megaphone text-info mb-3" style="font-size: 2rem;"></i>
                                        <h5>للحملات</h5>
                                        <p class="text-muted small">منصة لعرض الحملات الخيرية وجمع التبرعات</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- التسجيل والحساب -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-person-plus me-2"></i>
                            التسجيل والحساب
                        </h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                يجب أن تكون بالغاً (18 سنة أو أكثر) لاستخدام المنصة
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                يجب تقديم معلومات صحيحة ودقيقة عند التسجيل
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                أنت مسؤول عن الحفاظ على سرية كلمة المرور الخاصة بك
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <i class="bi bi-arrow-right text-primary me-2"></i>
                                يجب إشعارنا فوراً في حالة الاشتباه في اختراق حسابك
                            </li>
                        </ul>
                    </section>

                    <!-- قواعد التبرع -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-gift me-2"></i>
                            قواعد التبرع
                        </h2>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="bi bi-check-circle me-2"></i>
                                            المسموح
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="bi bi-check text-success me-2"></i>
                                                التبرع للحملات المعتمدة
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check text-success me-2"></i>
                                                التبرع المجهول
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check text-success me-2"></i>
                                                إضافة رسائل تشجيعية
                                            </li>
                                            <li class="mb-0">
                                                <i class="bi bi-check text-success me-2"></i>
                                                مشاركة الحملات
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="mb-0">
                                            <i class="bi bi-x-circle me-2"></i>
                                            غير المسموح
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="bi bi-x text-danger me-2"></i>
                                                التبرع بأموال غير مشروعة
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-x text-danger me-2"></i>
                                                إنشاء حملات وهمية
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-x text-danger me-2"></i>
                                                استخدام معلومات مزيفة
                                            </li>
                                            <li class="mb-0">
                                                <i class="bi bi-x text-danger me-2"></i>
                                                المحتوى المسيء أو المخالف
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- المدفوعات والاسترداد -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-credit-card me-2"></i>
                            المدفوعات والاسترداد
                        </h2>
                        
                        <div class="alert alert-warning border-0 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle text-warning me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>تنبيه مهم:</strong>
                                    <p class="mb-0 mt-2">جميع التبرعات نهائية ولا يمكن استردادها إلا في حالات استثنائية محددة</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <h5>مدفوعات آمنة</h5>
                                    <p class="text-muted small">جميع المعاملات محمية بأعلى معايير الأمان</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <h5>إيصالات فورية</h5>
                                    <p class="text-muted small">تلقي إيصال فوري لكل تبرع</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <h5>تتبع التبرعات</h5>
                                    <p class="text-muted small">متابعة جميع تبرعاتك من حسابك</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- المسؤوليات -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-balance me-2"></i>
                            المسؤوليات
                        </h2>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5 class="text-success mb-3">
                                    <i class="bi bi-building me-2"></i>
                                    مسؤوليات المنصة
                                </h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success me-2"></i>
                                        توفير خدمة آمنة وموثوقة
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success me-2"></i>
                                        حماية بيانات المستخدمين
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success me-2"></i>
                                        التحقق من صحة الحملات
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success me-2"></i>
                                        تقديم الدعم الفني
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5 class="text-info mb-3">
                                    <i class="bi bi-person me-2"></i>
                                    مسؤوليات المستخدم
                                </h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-info me-2"></i>
                                        استخدام المنصة بطريقة قانونية
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-info me-2"></i>
                                        تقديم معلومات صحيحة
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-info me-2"></i>
                                        احترام المستخدمين الآخرين
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-info me-2"></i>
                                        الإبلاغ عن أي مشاكل
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- إنهاء الخدمة -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-x-octagon me-2"></i>
                            إنهاء الخدمة
                        </h2>
                        <p class="text-muted lh-lg mb-3">
                            نحتفظ بالحق في إيقاف أو إنهاء حسابك في حالة مخالفة هذه الشروط أو استخدام المنصة بطريقة غير قانونية.
                        </p>
                        <div class="alert alert-danger border-0">
                            <h6 class="text-danger mb-2">أسباب إنهاء الحساب:</h6>
                            <ul class="mb-0">
                                <li>مخالفة شروط الاستخدام</li>
                                <li>استخدام معلومات مزيفة</li>
                                <li>محاولة الاحتيال أو غسيل الأموال</li>
                                <li>السلوك المسيء تجاه المستخدمين الآخرين</li>
                            </ul>
                        </div>
                    </section>

                    <!-- تعديل الشروط -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            تعديل الشروط
                        </h2>
                        <p class="text-muted lh-lg">
                            نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم إشعارك بأي تغييرات مهمة عبر البريد الإلكتروني أو من خلال إشعار على المنصة.
                        </p>
                    </section>

                    <!-- القانون المطبق -->
                    <section class="mb-5">
                        <h2 class="h3 text-primary mb-4">
                            <i class="bi bi-flag me-2"></i>
                            القانون المطبق
                        </h2>
                        <p class="text-muted lh-lg">
                            تخضع هذه الشروط والأحكام لقوانين المملكة العربية السعودية، وأي نزاع ينشأ عن استخدام المنصة سيتم حله وفقاً للقوانين السارية.
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
                                إذا كان لديك أي استفسارات حول شروط الاستخدام، يمكنك التواصل معنا:
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <i class="bi bi-envelope me-2"></i>
                                        <strong>البريد الإلكتروني:</strong> support@nabdhayah.com
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
