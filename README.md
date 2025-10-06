# نبض الحياة - منصة التبرعات الخيرية

## Nabd Hayah - Charitable Donations Platform

![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/1.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/2.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/3.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/4.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/5.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/6.png)
![screenshot](https://github.com/KiroCarllos/nbd-hayah-app/blob/main/public/website-screemshots/7.png)

منصة شاملة للتبرعات الخيرية تتيح للمستخدمين إنشاء حملات خيرية والتبرع لها من خلال نظام محفظة إلكترونية آمن، مع واجهة ويب متكاملة ولوحة تحكم إدارية شاملة وAPI للتطبيقات المحمولة.

## نظرة عامة على النظام

**نبض الحياة** هو نظام متكامل يتكون من ثلاث منصات رئيسية:

### 1. الموقع الإلكتروني (Web Platform)

موقع ويب متجاوب باللغة العربية يوفر تجربة مستخدم سلسة للمتبرعين والمستفيدين

### 2. لوحة التحكم الإدارية (Admin Dashboard)

نظام إدارة شامل لمراقبة وإدارة الحملات والمستخدمين والتبرعات والتقارير

### 3. واجهة برمجة التطبيقات (REST API)

API متكامل لدعم التطبيقات المحمولة مع نظام مصادقة آمن

## الميزات الأساسية

### إدارة الحملات

-   إنشاء وتعديل الحملات الخيرية
-   رفع صور متعددة للحملات
-   تحديد أولوية الحملات للعرض في الشريط المنزلق
-   إضافة تحديثات دورية للحملات
-   تتبع تقدم الحملات في الوقت الفعلي

### نظام المحفظة الإلكترونية

-   محفظة إلكترونية آمنة لكل مستخدم
-   حماية بكلمة مرور مكونة من 6 أرقام
-   شحن المحفظة عبر بوابة URWAY للدفع
-   تتبع شامل لجميع المعاملات المالية
-   نظام تحقق مؤقت لمدة 5 دقائق

### إدارة التبرعات

-   التبرع للحملات من رصيد المحفظة
-   خيار التبرع المجهول
-   التبرعات السريعة والعامة
-   تتبع تاريخ التبرعات الشخصية
-   إشعارات فورية عند إتمام التبرعات

### نظام المستخدمين

-   تسجيل المستخدمين مع التحقق من البيانات
-   إدارة الملفات الشخصية والصور
-   نظام المفضلة للحملات
-   إحصائيات شخصية للمستخدمين

## التقنيات المستخدمة

### Backend Framework

-   **Laravel 10** - إطار عمل PHP الحديث
-   **PHP 8.1+** - لغة البرمجة الأساسية
-   **MySQL** - قاعدة البيانات الرئيسية

### Frontend Technologies

-   **Bootstrap 5** - إطار عمل CSS للتصميم المتجاوب
-   **JavaScript/jQuery** - للتفاعلات الديناميكية
-   **Vite** - أداة البناء والتطوير
-   **RTL Support** - دعم كامل للغة العربية

### Authentication & Security

-   **Laravel Sanctum** - نظام المصادقة للAPI
-   **CSRF Protection** - حماية من هجمات التزوير
-   **Password Hashing** - تشفير كلمات المرور
-   **Session Management** - إدارة الجلسات الآمنة

### Payment Integration

-   **URWAY Payment Gateway** - بوابة الدفع الإلكتروني
-   **Secure Transactions** - معاملات مالية آمنة
-   **Callback Handling** - معالجة ردود بوابة الدفع

### Additional Services

-   **Firebase Cloud Messaging (FCM)** - الإشعارات الفورية
-   **L5-Swagger** - توثيق API تلقائي
-   **File Storage** - نظام إدارة الملفات والصور

## هيكل المشروع

### الواجهات الرئيسية

#### الموقع الإلكتروني (Web Interface)

```
/                    - الصفحة الرئيسية مع الحملات المميزة
/campaigns           - عرض جميع الحملات
/campaigns/{id}      - تفاصيل الحملة والتبرع
/wallet              - إدارة المحفظة الإلكترونية
/wallet/charge       - شحن المحفظة
/profile             - الملف الشخصي
/my-donations        - تاريخ التبرعات
/my-favorites        - الحملات المفضلة
/login               - تسجيل الدخول
/register            - إنشاء حساب جديد
```

#### لوحة التحكم الإدارية (Admin Dashboard)

```
/dashboard                    - الإحصائيات العامة
/dashboard/campaigns          - إدارة الحملات
/dashboard/users              - إدارة المستخدمين
/dashboard/donations          - إدارة التبرعات
/dashboard/transactions       - المعاملات المالية
/dashboard/reports            - التقارير والإحصائيات
/dashboard/sliders            - إدارة الشرائح المنزلقة
```

#### واجهة برمجة التطبيقات (API Endpoints)

```
GET  /api/campaigns           - قائمة الحملات
GET  /api/campaigns/{id}      - تفاصيل حملة
POST /api/campaigns/{id}/donate - التبرع للحملة
GET  /api/profile             - الملف الشخصي
POST /api/wallet/charge       - شحن المحفظة
GET  /api/my-donations        - تبرعات المستخدم
POST /api/campaigns/{id}/favorite - إضافة/إزالة من المفضلة
```

### قاعدة البيانات

#### الجداول الأساسية

-   **users** - بيانات المستخدمين والمحافظ
-   **campaigns** - الحملات الخيرية
-   **donations** - التبرعات
-   **wallet_transactions** - المعاملات المالية
-   **campaign_updates** - تحديثات الحملات
-   **favorites** - الحملات المفضلة
-   **sliders** - الشرائح المنزلقة

## متطلبات التشغيل

### متطلبات الخادم

-   **PHP 8.1** أو أحدث
-   **Composer** لإدارة حزم PHP
-   **Node.js & NPM** لأدوات Frontend
-   **MySQL 8.0** أو أحدث
-   **Apache/Nginx** خادم الويب

### الحزم المطلوبة

-   Laravel Framework 10.x
-   Laravel Sanctum للمصادقة
-   L5-Swagger لتوثيق API
-   Bootstrap 5 للواجهة
-   jQuery للتفاعلات

## التثبيت والإعداد

### 1. استنساخ المشروع

```bash
git clone [repository-url]
cd nabd-hayah-app
```

### 2. تثبيت التبعيات

```bash
# تثبيت حزم PHP
composer install

# تثبيت حزم JavaScript
npm install
```

### 3. إعداد البيئة

```bash
# نسخ ملف البيئة
cp .env.example .env

# توليد مفتاح التطبيق
php artisan key:generate
```

### 4. إعداد قاعدة البيانات

```bash
# تشغيل الهجرات
php artisan migrate

# تشغيل البذور (اختياري)
php artisan db:seed
```

### 5. إعداد التخزين

```bash
# ربط مجلد التخزين العام
php artisan storage:link
```

### 6. بناء الأصول

```bash
# للتطوير
npm run dev

# للإنتاج
npm run build
```

## الإعدادات المطلوبة

### متغيرات البيئة (.env)

```env
# إعدادات التطبيق
APP_NAME="نبض الحياة"
APP_ENV=production
APP_KEY=base64:your-app-key
APP_DEBUG=false
APP_URL=http://your-domain.com

# إعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nabd_hayah
DB_USERNAME=your_username
DB_PASSWORD=your_password

# إعدادات بوابة الدفع URWAY
URWAY_TERMINAL_ID=your_terminal_id
URWAY_PASSWORD=your_password
URWAY_MERCHANT_KEY=your_merchant_key
URWAY_TEST_MODE=false

# إعدادات Firebase للإشعارات
FCM_SERVER_KEY=your_fcm_server_key
FCM_SENDER_ID=your_sender_id

# إعدادات البريد الإلكتروني
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### إعداد صلاحيات الملفات

```bash
# إعداد صلاحيات مجلدات Laravel
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## تشغيل التطبيق

### للتطوير

```bash
# تشغيل خادم Laravel
php artisan serve

# تشغيل Vite للتطوير (في terminal منفصل)
npm run dev
```

### للإنتاج

```bash
# تحسين التطبيق للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache

# بناء الأصول للإنتاج
npm run build
```

## الميزات المتقدمة

### نظام التقارير والإحصائيات

-   إحصائيات شاملة للحملات والتبرعات
-   تقارير مالية مفصلة
-   تحليل أداء الحملات
-   إحصائيات المستخدمين والمتبرعين
-   تصدير التقارير بصيغ مختلفة

### نظام الأمان المتقدم

-   حماية كلمة مرور المحفظة بنظام OTP
-   تشفير البيانات الحساسة
-   مراجعة شاملة لجميع العمليات المالية
-   نظام تنبيهات للعمليات المشبوهة
-   جلسات آمنة مع انتهاء صلاحية تلقائي

### التكامل مع الخدمات الخارجية

-   بوابة URWAY للدفع الإلكتروني
-   Firebase للإشعارات الفورية
-   خدمات البريد الإلكتروني
-   نظام النسخ الاحتياطي التلقائي

## API Documentation

يمكن الوصول لتوثيق API الكامل عبر:

```
http://your-domain.com/api/documentation
```

### نقاط API الرئيسية

#### المصادقة

```http
POST /api/login
POST /api/register
POST /api/logout
GET  /api/me
```

#### الحملات

```http
GET  /api/campaigns
GET  /api/campaigns/{id}
POST /api/campaigns
POST /api/campaigns/{id}/favorite
GET  /api/my-campaigns
GET  /api/favorites
```

#### التبرعات والمحفظة

```http
POST /api/campaigns/{id}/donate
GET  /api/my-donations
POST /api/wallet/charge
GET  /api/wallet/transactions
POST /api/wallet/password/set
POST /api/wallet/password/verify
```

## الصيانة والمراقبة

### المراقبة اليومية

-   فحص سجلات الأخطاء
-   مراقبة أداء قاعدة البيانات
-   تتبع المعاملات المالية
-   مراجعة الإحصائيات اليومية

### النسخ الاحتياطية

```bash
# نسخ احتياطي لقاعدة البيانات
php artisan backup:run

# نسخ احتياطي للملفات
tar -czf backup-files.tar.gz storage/app/public
```

### التحديثات

```bash
# تحديث التبعيات
composer update
npm update

# تشغيل الهجرات الجديدة
php artisan migrate

# إعادة بناء الكاش
php artisan optimize:clear
php artisan optimize
```

## الدعم والمساهمة

### الإبلاغ عن المشاكل

يرجى الإبلاغ عن أي مشاكل أو اقتراحات عبر نظام Issues في المستودع

### المساهمة في التطوير

نرحب بالمساهمات من المطورين لتحسين النظام وإضافة ميزات جديدة

### الترخيص

هذا المشروع مرخص تحت رخصة MIT - راجع ملف LICENSE للتفاصيل

---

**تم تطوير هذا النظام لخدمة المجتمع وتسهيل عمليات التبرع الخيري بطريقة آمنة وموثوقة**
