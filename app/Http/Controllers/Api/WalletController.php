<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Http\Resources\UserResource;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * @OA\Get(
     *     path="/wallet",
     *     summary="الحصول على معلومات المحفظة",
     *     tags={"Wallet"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="معلومات المحفظة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="balance", type="number", format="float", example=500.00),
     *                 @OA\Property(property="total_donations", type="number", format="float", example=1200.00),
     *                 @OA\Property(property="donations_count", type="integer", example=15)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => (float) $user->wallet_balance,
                'total_donations' => (float) $user->donations()->sum('amount'),
                'donations_count' => $user->donations()->count(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/wallet/charge",
     *     summary="شحن المحفظة",
     *     tags={"Wallet"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", format="float", example=100.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إنشاء طلب الشحن بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء طلب الشحن بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="payment_url", type="string", example="https://payment.gateway.com/pay/123"),
     *                 @OA\Property(property="transaction_id", type="string", example="TXN123456")
     *             )
     *         )
     *     )
     * )
     */
    public function charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10|max:10000',
        ], [
            'amount.required' => 'مبلغ الشحن مطلوب',
            'amount.numeric' => 'مبلغ الشحن يجب أن يكون رقم',
            'amount.min' => 'الحد الأدنى للشحن 10 ريال',
            'amount.max' => 'الحد الأقصى للشحن 10000 ريال',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $amount = $request->amount;

        // Create wallet transaction record
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'charge',
            'status' => 'pending',
            'transaction_id' => 'TXN' . time() . rand(1000, 9999),
        ]);

        // Here you would integrate with URWAY payment gateway
        // For now, we'll return a mock response
        $paymentUrl = "https://payment.gateway.com/pay/" . $transaction->transaction_id;

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء طلب الشحن بنجاح',
            'data' => [
                'payment_url' => $paymentUrl,
                'transaction_id' => $transaction->transaction_id,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/wallet/transactions",
     *     summary="الحصول على تاريخ المعاملات",
     *     tags={"Wallet"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="رقم الصفحة",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تاريخ المعاملات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="amount", type="number", format="float", example=100.00),
     *                 @OA\Property(property="type", type="string", example="charge"),
     *                 @OA\Property(property="status", type="string", example="completed"),
     *                 @OA\Property(property="transaction_id", type="string", example="TXN123456"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *             ))
     *         )
     *     )
     * )
     */
    public function transactions(Request $request)
    {
        $transactions = auth()->user()->walletTransactions()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ]
        ]);
    }
}
