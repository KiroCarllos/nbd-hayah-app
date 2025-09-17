<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="نبض الحياة API",
 *     version="1.0.0",
 *     description="API للتطبيق الخيري نبض الحياة",
 *     @OA\Contact(
 *         email="info@nabdhayah.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="تسجيل مستخدم جديد",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","mobile","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="mobile", type="string", example="01234567890"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تم التسجيل بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التسجيل بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", example="1|abc123...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطأ في البيانات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطأ في البيانات"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|unique:users,mobile|regex:/^01[0-9]{9}$/',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'الاسم مطلوب',
            'mobile.required' => 'رقم الهاتف مطلوب',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'mobile.regex' => 'رقم الهاتف غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'wallet_balance' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم التسجيل بنجاح',
            'data' => new UserResource($user),
            'token' => $token
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="تسجيل الدخول",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mobile","password"},
     *             @OA\Property(property="mobile", type="string", example="01234567890"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تسجيل الدخول بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تسجيل الدخول بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", example="1|abc123...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="بيانات الدخول غير صحيحة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="بيانات الدخول غير صحيحة")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'password' => 'required|string',
        ], [
            'mobile.required' => 'رقم الهاتف مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات الدخول غير صحيحة'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => new UserResource($user),
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="تسجيل الخروج",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تم تسجيل الخروج بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تسجيل الخروج بنجاح")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="الحصول على بيانات المستخدم الحالي",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="بيانات المستخدم",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user())
        ]);
    }
}
