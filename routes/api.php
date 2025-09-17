<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public campaigns routes
Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/{id}', [CampaignController::class, 'show']);

// Statistics endpoint
Route::get('/statistics', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'total_campaigns' => \App\Models\Campaign::active()->count(),
            'total_users' => \App\Models\User::whereHas('donations')->count(),
            'total_donations' => \App\Models\Donation::count(),
            'total_amount' => \App\Models\Donation::completed()->sum('amount'),
        ]
    ]);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User routes
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/image', [UserController::class, 'updateProfileImage']);

    // Campaign routes
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::post('/campaigns/{id}/favorite', [CampaignController::class, 'toggleFavorite']);
    Route::get('/my-campaigns', [CampaignController::class, 'myCampaigns']);
    Route::get('/favorites', [CampaignController::class, 'favorites']);

    // Donation routes
    Route::apiResource('donations', DonationController::class)->only(['index', 'store', 'show']);
    Route::post('/campaigns/{id}/donate', [DonationController::class, 'donate']);

    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'index']);
    Route::post('/wallet/charge', [WalletController::class, 'charge']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
});
