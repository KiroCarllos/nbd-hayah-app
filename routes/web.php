<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;
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
});

// Payment callback (no auth required)
Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');

// Admin routes
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
});
