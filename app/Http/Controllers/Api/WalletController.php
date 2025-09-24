<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Http\Resources\UserResource;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
//    public function charge(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'amount' => 'required|numeric|min:10|max:10000',
//        ], [
//            'amount.required' => 'مبلغ الشحن مطلوب',
//            'amount.numeric' => 'مبلغ الشحن يجب أن يكون رقم',
//            'amount.min' => 'الحد الأدنى للشحن 10 ريال',
//            'amount.max' => 'الحد الأقصى للشحن 10000 ريال',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'success' => false,
//                'message' => 'خطأ في البيانات',
//                'errors' => $validator->errors()
//            ], 422);
//        }
//
//        $user = auth()->user();
//        $amount = $request->amount;
//
//
//
//        $invoiceNo = 'WLT_' . $user->id . '_' . time();
//        // Create wallet transaction record
//        $transaction = WalletTransaction::create([
//            'user_id' => $user->id,
//            'amount' => $amount,
//            'description' => 'شحن المحفظة',
//            'type' => 'credit',
//            'status' => 'pending',
//            'reference' => $invoiceNo,
//            'payment_data' => null,
//        ]);
//
//        // Here you would integrate with URWAY payment gateway
//        // For now, we'll return a mock response
//        $paymentUrl = "https://payment.gateway.com/pay/" . $transaction->transaction_id;
//
//        return response()->json([
//            'success' => true,
//            'message' => 'تم إنشاء طلب الشحن بنجاح',
//            'data' => [
//                'payment_url' => $paymentUrl,
//                'transaction_id' => $transaction->transaction_id,
//            ]
//        ]);
//    }


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
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصدق'
            ], 401);
        }

//        if ($user->hasWalletPassword() && !WalletPasswordController::isWalletPasswordVerified()) {
//            return response()->json([
//                'success' => false,
//                'needs_wallet_password' => true,
//                'message' => 'يرجى إدخال كلمة مرور المحفظة للمتابعة',
//                'return_url' => route('wallet.charge.process'), // client يمكن يخزن return_data ويعيد المحاولة بعد التحقق
//                'return_data' => $request->all()
//            ], 403);
//        }

        $amount = $request->amount;
        $invoiceNo = 'WLT_' . $user->id . '_' . time();

        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'description' => 'شحن المحفظة',
            'type' => 'credit',
            'status' => 'pending',
            'reference' => $invoiceNo,
            'payment_data' => null,
        ]);

        $serverIp = $this->getServerIp();
        $txnDetails = $invoiceNo . '|' . $this->terminalId . '|' . $this->password . '|' . $this->merchantKey . '|' . $amount . '|' . $this->currency;
        $hash = hash('sha256', $txnDetails);

        $fields = [
            'trackid' => $invoiceNo,
            'terminalId' => $this->terminalId,
            'customerEmail' => $user->email,
            'action' => "1",
            'merchantIp' => $serverIp,
            'password' => $this->password,
            'currency' => $this->currency,
            'country' => $this->country,
            'amount' => $amount,
            "udf1" => "Wallet Charge",
            "udf2" => route('payment.callback'),
            "udf3" => $user->id,
            "udf4" => $transaction->id,
            "udf5" => "Nabd Hayah",
            'requestHash' => $hash
        ];

        $data = json_encode($fields);

        $ch = curl_init($this->paymentUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        // ⚠️ في بيئة الإنتاج تأكد من التحقق SSL:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $serverOutput = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($serverOutput === false || $httpCode !== 200) {
            Log::error('Payment gateway request failed', [
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'http_code' => $httpCode,
                'curl_error' => $curlErr,
                'response' => $serverOutput
            ]);

            $transaction->update([
                'status' => 'failed',
                'payment_data' => array_merge($transaction->payment_data ?? [], [
                    'request_data' => $fields,
                    'gateway_response' => $serverOutput,
                    'error' => $curlErr ?: "HTTP_CODE_$httpCode"
                ])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في الاتصال بخدمة الدفع'
            ], 500);
        }

        $result = json_decode($serverOutput);

        if (!empty($result->payid) && !empty($result->targetUrl)) {
            $url = $result->targetUrl . '?paymentid=' . $result->payid;

            $transaction->update([
                'payment_data' => [
                    'payid' => $result->payid,
                    'target_url' => $result->targetUrl,
                    'request_data' => $fields,
                    'response_data' => $result
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء طلب الدفع بنجاح',
                'data' => [
                    'payment_url' => $url,
                    'payid' => $result->payid,
                    'transaction' => [
                        'id' => $transaction->id,
                        'reference' => $transaction->reference,
                        // If you have a separate transaction_id field include it too:
                        'transaction_id' => $transaction->transaction_id ?? null,
                        'amount' => $transaction->amount
                    ]
                ]
            ], 201);
        } else {
            Log::error('Payment gateway returned invalid response', [
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'response' => $serverOutput
            ]);

            $transaction->update([
                'status' => 'failed',
                'payment_data' => array_merge($transaction->payment_data ?? [], [
                    'request_data' => $fields,
                    'response_data' => $result,
                ])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء عملية الدفع'
            ], 500);
        }
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
//        if(auth()->user()){
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
//        }else{
//            return response()->json(['success' =>false
//        }

    }
    private function getServerIp()
    {
        $serverIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';

        if ($serverIp === '127.0.0.1' || $serverIp === '::1') {
            $serverIp = '127.0.0.1'; // For development
        }

        return $serverIp;
    }
}
