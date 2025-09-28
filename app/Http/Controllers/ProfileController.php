<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\GeneralDonation;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();

        // Get user statistics
        $campaignDonations = Donation::where('user_id', $user->id)->where('status', 'completed');
        $generalDonations = GeneralDonation::where('user_id', $user->id)->where('status', 'completed');

        $stats = [
            'total_donations' => $campaignDonations->count() + $generalDonations->count(),
            'total_donated_amount' => $campaignDonations->sum('amount') + $generalDonations->sum('amount'),
            'campaign_donations' => $campaignDonations->count(),
            'general_donations' => $generalDonations->count(),
            'total_wallet_transactions' => WalletTransaction::where('user_id', $user->id)->count(),
            'total_charged_amount' => WalletTransaction::where('user_id', $user->id)->where('type', 'credit')->where('status', 'completed')->sum('amount'),
        ];

        // Recent transactions
        $recent_transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent campaign donations
        $recent_donations = Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent general donations
        $recent_general_donations = GeneralDonation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('profile.show', compact('user', 'stats', 'recent_transactions', 'recent_donations', 'recent_general_donations'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|min:2|max:255|regex:/^[\p{Arabic}\p{L}\s]+$/u',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'required|regex:/^01[0-2,5][0-9]{8}$/|unique:users,mobile,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/|confirmed',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'الاسم يجب أن يكون حرفين على الأقل',
            'name.regex' => 'الاسم يجب أن يحتوي على أحرف عربية أو إنجليزية فقط',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'mobile.required' => 'رقم الهاتف مطلوب',
            'mobile.regex' => 'رقم الهاتف يجب أن يكون رقم مصري صحيح (01xxxxxxxxx)',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'profile_image.image' => 'يجب أن تكون الصورة من نوع صحيح',
            'profile_image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
            'current_password.required_with' => 'كلمة المرور الحالية مطلوبة لتغيير كلمة المرور',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير وصغير ورقم ورمز خاص',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        $sensitiveDataChanged = (
            $request->email !== $user->email ||
            $request->mobile !== $user->mobile ||
            $request->filled('password')
        );

        if ($sensitiveDataChanged) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية مطلوبة لتحديث البيانات الحساسة']);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
        }
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && $user->profile_image != "default.png") {
                Storage::disk('public')->delete($user->profile_image);
            }

            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
