<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class WalletPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if user has wallet password
     */
    public function hasPassword()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'has_password' => $user->hasWalletPassword()
        ]);
    }

    /**
     * Verify wallet password
     */
    public function verifyPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_password' => 'required|string|digits:6',
        ], [
            'wallet_password.required' => 'كلمة مرور المحفظة مطلوبة',
            'wallet_password.digits' => 'كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!$user->hasWalletPassword()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم تعيين كلمة مرور للمحفظة'
            ], 400);
        }

        if (!$user->checkWalletPassword($request->wallet_password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة مرور المحفظة غير صحيحة'
            ], 401);
        }

        // Store verification in session for a short time (5 minutes)
        session(['wallet_password_verified' => true, 'wallet_password_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من كلمة مرور المحفظة بنجاح'
        ]);
    }

    /**
     * Set wallet password
     */
    public function setPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_wallet_password' => 'required|string|digits:6',
            'confirm_wallet_password' => 'required|string|same:new_wallet_password',
        ], [
            'new_wallet_password.required' => 'كلمة مرور المحفظة الجديدة مطلوبة',
            'new_wallet_password.digits' => 'كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط',
            'confirm_wallet_password.required' => 'تأكيد كلمة مرور المحفظة مطلوب',
            'confirm_wallet_password.same' => 'كلمة مرور المحفظة غير متطابقة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $user->setWalletPassword($request->new_wallet_password);

        // Store verification in session
        session(['wallet_password_verified' => true, 'wallet_password_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين كلمة مرور المحفظة بنجاح'
        ]);
    }

    /**
     * Change wallet password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_wallet_password' => 'required|string|digits:6',
            'new_wallet_password' => 'required|string|digits:6',
            'confirm_wallet_password' => 'required|string|same:new_wallet_password',
        ], [
            'current_wallet_password.required' => 'كلمة مرور المحفظة الحالية مطلوبة',
            'current_wallet_password.digits' => 'كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط',
            'new_wallet_password.required' => 'كلمة مرور المحفظة الجديدة مطلوبة',
            'new_wallet_password.digits' => 'كلمة مرور المحفظة يجب أن تكون 6 أرقام فقط',
            'confirm_wallet_password.required' => 'تأكيد كلمة مرور المحفظة مطلوب',
            'confirm_wallet_password.same' => 'كلمة مرور المحفظة غير متطابقة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!$user->hasWalletPassword()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم تعيين كلمة مرور للمحفظة'
            ], 400);
        }

        if (!$user->checkWalletPassword($request->current_wallet_password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة مرور المحفظة الحالية غير صحيحة'
            ], 401);
        }

        $user->setWalletPassword($request->new_wallet_password);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة مرور المحفظة بنجاح'
        ]);
    }

    /**
     * Check if wallet password is verified in current session
     */
    public static function isWalletPasswordVerified()
    {
        if (!session('wallet_password_verified')) {
            return false;
        }

        $verifiedAt = session('wallet_password_verified_at');
        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) > 5) {
            // Clear expired verification
            session()->forget(['wallet_password_verified', 'wallet_password_verified_at']);
            return false;
        }

        return true;
    }

    /**
     * Clear wallet password verification from session
     */
    public function clearVerification()
    {
        session()->forget(['wallet_password_verified', 'wallet_password_verified_at']);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء التحقق من كلمة مرور المحفظة'
        ]);
    }
}
