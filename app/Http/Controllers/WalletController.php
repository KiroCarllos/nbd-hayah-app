<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function showChargeForm()
    {
        return view('wallet.charge');
    }

    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ], [
            'amount.required' => 'مبلغ الشحن مطلوب',
            'amount.numeric' => 'مبلغ الشحن يجب أن يكون رقماً',
            'amount.min' => 'الحد الأدنى للشحن هو 1 ريال',
        ]);

        // Redirect to payment gateway
        return app(PaymentController::class)->redirectToPaymentPage($request);
    }

    public function adminIndex()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $transactions = WalletTransaction::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }
}
