<?php

use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GeneralDonationController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuickDonationController;
use App\Http\Controllers\WalletController;
use App\Services\FirebaseFcm;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/testFCM', function (){
    $fcm = new FirebaseFcm();
    // حط هنا الـ Device Token الخاص بالموبايل اللي هتجرب عليه
    $deviceToken = request()->get("device_token");

    // تجربة إرسال إشعار
    $response = $fcm->sendToDevice(
        $deviceToken,
        'تجربة إشعار',
        'ده إشعار تجريبي من السيرفر',
    );

    return response()->json($response);
});

// Campaign routes
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::post('/campaigns/{campaign}/favorite', [CampaignController::class, 'toggleFavorite'])->name('campaigns.favorite');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/charge', [WalletController::class, 'showChargeForm'])->name('wallet.charge');
    Route::post('/wallet/charge', [WalletController::class, 'charge'])->name('wallet.charge.process');

    // Donation routes
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'donate'])->name('campaigns.donate');
    Route::get('/my-donations', [DonationController::class, 'myDonations'])->name('donations.index');

    // Quick Donations
    Route::post('/quick-donate', [QuickDonationController::class, 'store'])->name('quick-donate.store');

    // Favorites
    Route::post('/campaigns/{campaign}/favorite', [FavoriteController::class, 'toggle'])->name('campaigns.favorite.toggle');
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Payment callback (no auth required)
Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');

// Admin routes
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Campaign management
    Route::resource('campaigns', AdminCampaignController::class)->names([
        'index' => 'admin.campaigns.index',
        'create' => 'admin.campaigns.create',
        'store' => 'admin.campaigns.store',
        'show' => 'admin.campaigns.show',
        'edit' => 'admin.campaigns.edit',
        'update' => 'admin.campaigns.update',
        'destroy' => 'admin.campaigns.destroy',
    ]);

    // Campaign Updates management
    Route::resource('campaigns.updates', \App\Http\Controllers\Admin\AdminCampaignUpdateController::class)->names([
        'index' => 'admin.campaign-updates.index',
        'create' => 'admin.campaign-updates.create',
        'store' => 'admin.campaign-updates.store',
        'show' => 'admin.campaign-updates.show',
        'edit' => 'admin.campaign-updates.edit',
        'update' => 'admin.campaign-updates.update',
        'destroy' => 'admin.campaign-updates.destroy',
    ]);

    // User management
    Route::resource('users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Donations management
    Route::get('donations', [DonationController::class, 'adminIndex'])->name('admin.donations.index');

    // General Donations management
    Route::get('general-donations', [GeneralDonationController::class, 'index'])->name('admin.general-donations.index');
    Route::get('general-donations/{generalDonation}', [GeneralDonationController::class, 'show'])->name('admin.general-donations.show');

    // Transactions management
    Route::get('transactions', [WalletController::class, 'adminIndex'])->name('admin.transactions.index');

    // Slider management
    Route::resource('sliders', SliderController::class)->names([
        'index' => 'admin.sliders.index',
        'create' => 'admin.sliders.create',
        'store' => 'admin.sliders.store',
        'edit' => 'admin.sliders.edit',
        'update' => 'admin.sliders.update',
        'destroy' => 'admin.sliders.destroy',
    ]);

    // Reports
    Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/campaign/{id}', [App\Http\Controllers\ReportsController::class, 'campaignDetails'])->name('admin.reports.campaign-details');
    Route::get('reports/user/{id}', [App\Http\Controllers\ReportsController::class, 'userDetails'])->name('admin.reports.user-details');
    Route::get('reports/export', [App\Http\Controllers\ReportsController::class, 'export'])->name('admin.reports.export');
});

// Legal Pages (Public)
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Mobile App Legal Pages (without layout)
Route::get('/mobile/privacy', function () {
    return view('mobile.privacy');
})->name('mobile.privacy');

Route::get('/mobile/terms', function () {
    return view('mobile.terms');
})->name('mobile.terms');
