<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Wallet Password",
 *     description="API endpoints for wallet password management"
 * )
 */
class WalletPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/wallet/has-password",
     *     summary="Check if user has wallet password",
     *     tags={"Wallet Password"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="has_password", type="boolean", example=true)
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/wallet/verify-password",
     *     summary="Verify wallet password",
     *     tags={"Wallet Password"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="wallet_password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التحقق من كلمة مرور المحفظة بنجاح")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid password",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="كلمة مرور المحفظة غير صحيحة")
     *         )
     *     )
     * )
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

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من كلمة مرور المحفظة بنجاح'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/wallet/set-password",
     *     summary="Set wallet password",
     *     tags={"Wallet Password"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="new_wallet_password", type="string", example="123456", description="كلمة مرور المحفظة الجديدة (6 أرقام)"),
     *             @OA\Property(property="confirm_wallet_password", type="string", example="123456", description="تأكيد كلمة مرور المحفظة")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تعيين كلمة مرور المحفظة بنجاح")
     *         )
     *     )
     * )
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

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين كلمة مرور المحفظة بنجاح'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/wallet/change-password",
     *     summary="Change wallet password",
     *     tags={"Wallet Password"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="current_wallet_password", type="string", example="123456", description="كلمة مرور المحفظة الحالية (6 أرقام)"),
     *             @OA\Property(property="new_wallet_password", type="string", example="654321", description="كلمة مرور المحفظة الجديدة (6 أرقام)"),
     *             @OA\Property(property="confirm_wallet_password", type="string", example="654321", description="تأكيد كلمة مرور المحفظة")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تغيير كلمة مرور المحفظة بنجاح")
     *         )
     *     )
     * )
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
}
