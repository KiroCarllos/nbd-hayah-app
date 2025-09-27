<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            // Email regex: only gmail, yahoo, hotmail, outlook
            'email' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'regex:/^[a-z0-9]+@(gmail|yahoo|hotmail|outlook)\.com$/i'
            ],

            // Mobile regex: 01 + (0,1,2,5) + 8 digits
            'mobile' => [
                'required',
                'string',
                'max:20',
                'unique:users',
                'regex:/^01[0-2,5]\d{8}$/'
            ],

            // Password: at least 8 chars, 1 upper, 1 lower, 1 digit, 1 special char
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],

            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.regex' => 'يجب أن يكون البريد الإلكتروني على أحد النطاقات: gmail, yahoo, hotmail, outlook',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',

            'mobile.required' => 'رقم الموبايل مطلوب',
            'mobile.regex' => 'رقم الموبايل غير صحيح. يجب أن يبدأ بـ 010 أو 011 أو 012 أو 015 ويليه 8 أرقام.',
            'mobile.unique' => 'رقم الموبايل مستخدم من قبل',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، وحرف صغير، ورقم، ورمز خاص.',

            'profile_image.image' => 'يجب أن يكون الملف صورة',
            'profile_image.mimes' => 'نوع الصورة غير مدعوم. الأنواع المسموح بها: jpeg, png, jpg, gif',
            'profile_image.max' => 'حجم الصورة كبير جداً، الحد الأقصى 2 ميجابايت',
        ];
    }

}
