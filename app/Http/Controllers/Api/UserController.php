<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profile",
     *     summary="الحصول على الملف الشخصي",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="الملف الشخصي",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource(auth()->user())
        ]);
    }

    /**
     * @OA\Post(
     *     path="/profile",
     *     summary="تحديث الملف الشخصي",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="mobile", type="string", example="01234567890"),
     *             @OA\Property(property="current_password", type="string", example="oldpassword"),
     *             @OA\Property(property="password", type="string", example="newpassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث الملف الشخصي بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث الملف الشخصي بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'mobile' => 'sometimes|string|unique:users,mobile,' . $user->id . '|regex:/^01[0-9]{9}$/',
        ];

        // If password is being updated
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.string' => 'الاسم يجب أن يكون نص',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'mobile.regex' => 'رقم الهاتف غير صحيح',
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
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

        // Check current password if updating password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور الحالية غير صحيحة'
                ], 400);
            }
            $user->password = Hash::make($request->password);
        }

        // Update other fields
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('mobile')) {
            $user->mobile = $request->mobile;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/profile/image",
     *     summary="تحديث صورة الملف الشخصي",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"image"},
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم تحديث الصورة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم تحديث الصورة بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'image.required' => 'الصورة مطلوبة',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();

        // Delete old image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store new image
        $path = $request->file('image')->store('profiles', 'public');
        $user->update(['profile_image' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصورة بنجاح',
            'data' => new UserResource($user)
        ]);
    }
}
