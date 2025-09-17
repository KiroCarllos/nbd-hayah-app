<?php

namespace App\Http\Controllers;

use App\Models\Donation;
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
        $stats = [
            'total_donations' => Donation::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_donated_amount' => Donation::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
            'total_wallet_transactions' => WalletTransaction::where('user_id', $user->id)->count(),
            'total_charged_amount' => WalletTransaction::where('user_id', $user->id)->where('type', 'credit')->where('status', 'completed')->sum('amount'),
        ];

        // Recent transactions
        $recent_transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent donations
        $recent_donations = Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('profile.show', compact('user', 'stats', 'recent_transactions', 'recent_donations'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|unique:users,mobile,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'mobile.required' => 'رقم الهاتف مطلوب',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'profile_image.image' => 'يجب أن تكون الصورة من نوع صحيح',
            'profile_image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        // Check current password if user wants to change password
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
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
