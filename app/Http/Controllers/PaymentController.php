<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\WalletPasswordController;

class PaymentController extends Controller
{
    private $terminalId = 'wesal';
    private $password = 'urway@123';
    private $merchantKey = 'a705c31a3011f11b4d5d05b4424b003722941122fa0ca153cced622358f175f7';
    private $currency = 'SAR';
    private $country = 'SA';
    private $paymentUrl = 'https://payments-dev.urway-tech.com/URWAYPGService/transaction/jsonProcess/JSONrequest';

    public function redirectToPaymentPage(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        // Log for debugging
        Log::info('Payment request received', [
            'user_id' => $user->id,
            'amount' => $request->amount,
            'session_id' => session()->getId(),
            'user_authenticated' => Auth::check(),
        ]);

        // Check if user has wallet password and needs verification
        if ($user->hasWalletPassword()) {
            // Check if wallet password is already verified in this session
            if (!WalletPasswordController::isWalletPasswordVerified()) {
                // Redirect to wallet password verification with return URL
                return redirect()->route('wallet.password.verify-form')
                    ->with('return_url', route('wallet.charge.process'))
                    ->with('return_data', $request->all())
                    ->with('message', 'يرجى إدخال كلمة مرور المحفظة للمتابعة');
            }
        }

        $amount = $request->amount;
        $invoiceNo = 'WLT_' . $user->id . '_' . time();

        // Create pending wallet transaction
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => 'شحن المحفظة',
            'reference' => $invoiceNo,
            'status' => 'pending',
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

        $serverOutput = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $transaction->update(['status' => 'failed']);
            return redirect()->route('wallet.index')->with('error', 'فشل في الاتصال بخدمة الدفع');
        }

        $result = json_decode($serverOutput);

        if (!empty($result->payid) && !empty($result->targetUrl)) {
            $url = $result->targetUrl . '?paymentid=' . $result->payid;

            // Update transaction with payment data
            $transaction->update([
                'payment_data' => [
                    'payid' => $result->payid,
                    'target_url' => $result->targetUrl,
                    'request_data' => $fields,
                    'response_data' => $result
                ]
            ]);

            return redirect($url);
        } else {
            $transaction->update(['status' => 'failed']);
            return redirect()->route('wallet.index')->with('error', 'فشل في إنشاء عملية الدفع');
        }
    }

    public function paymentCallback(Request $request)
    {
        $trackId = $request->input('TrackId');
        $result = $request->input('Result');
        $responseCode = $request->input('ResponseCode');
        $responseHash = $request->input('responseHash');
        $amount = $request->input('amount');
        if (!$trackId) {
            return redirect()->route('wallet.index')->with('error', 'بيانات الدفع غير صحيحة');
        }

        $transaction = WalletTransaction::where('reference', $trackId)->first();
        if (!$transaction) {
            return redirect()->route('wallet.index')->with('error', 'المعاملة غير موجودة');
        }

        // Verify response hash
        $hashString = $trackId . '|' . $this->terminalId . '|' . $this->password . '|' . $this->merchantKey . '|' . $amount . '|' . $this->currency;
        $calculatedHash = hash('sha256', $hashString);
        // dd($hashString, $responseHash, $calculatedHash);
        // if ($responseHash !== $calculatedHash) {
        //     $transaction->update([
        //         'status' => 'failed',
        //         'payment_data' => array_merge($transaction->payment_data ?? [], [
        //             'callback_data' => $request->all(),
        //             'error' => 'Hash verification failed'
        //         ])
        //     ]);
        //     dd('فشل في التحقق من صحة المعاملة');
        //     return redirect()->route('wallet.index')->with('error', 'فشل في التحقق من صحة المعاملة');
        // }

        DB::beginTransaction();
        try {
            // dd('تم شحن المحفظة بنجاح! تم إضافة ');
            // dd($result, $responseCode, $responseHash, $amount);
            // Check if transaction is successful based on result and response code
            if ($result === 'Successful' && $responseCode === '000') {
                // Update transaction status
                $transaction->update([
                    'status' => 'completed',
                    'payment_data' => array_merge($transaction->payment_data ?? [], [
                        'callback_data' => $request->all()
                    ])
                ]);

                // Update user wallet balance
                $user = $transaction->user;
                $user->increment('wallet_balance', $transaction->amount);
                if($user->device_token){
                    $fcm = FCM::sendToDevice(
                        $user->device_token,
                        'تم شحن محفظتك بنجاح ❤️',
                        'تم شحن المحفظة بنجاح! تم إضافة ' . number_format($transaction->amount, 2) . ' ر.س إلى رصيدك'
                    );
                }
                DB::commit();
                return redirect()->route('wallet.index')->with('success', 'تم شحن المحفظة بنجاح! تم إضافة ' . number_format($transaction->amount, 2) . ' ر.س إلى رصيدك');
            } else {
                // Handle different error codes
                $errorMessage = $this->getErrorMessage($responseCode);

                $transaction->update([
                    'status' => 'failed',
                    'payment_data' => array_merge($transaction->payment_data ?? [], [
                        'callback_data' => $request->all(),
                        'error_code' => $responseCode,
                        'error_message' => $errorMessage,
                        'processed_at' => now()
                    ])
                ]);

                DB::commit();
                return redirect()->route('wallet.index')->with('error', 'فشلت عملية الدفع: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('wallet.index')->with('error', 'حدث خطأ أثناء معالجة المعاملة');
        }
    }

    private function getErrorMessage($responseCode)
    {
        $errorMessages = [
            '000' => 'العملية تمت بنجاح',
            '601' => 'خطأ في النظام، يرجى التواصل مع الإدارة',
            '659' => 'فشل في المصادقة',
            '701' => 'خطأ في معالجة رمز الدفع',
            '906' => 'رمز البطاقة غير صحيح',
        ];

        // Handle 5XX bank rejections
        if (substr($responseCode, 0, 1) === '5') {
            return 'رفض من البنك - يرجى التحقق من بيانات البطاقة أو التواصل مع البنك';
        }

        return $errorMessages[$responseCode] ?? 'خطأ غير معروف: ' . $responseCode;
    }

    private function getServerIp()
    {
        // Get server IP address
        $serverIp = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';

        // If running locally, use a public IP or the local IP
        if ($serverIp === '127.0.0.1' || $serverIp === '::1') {
            // You can use a service to get public IP or use a fixed IP for testing
            $serverIp = '127.0.0.1'; // For development
        }

        return $serverIp;
    }
}
