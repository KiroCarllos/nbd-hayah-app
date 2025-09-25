<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\GeneralDonation;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuickDonationController extends Controller
{


    /**
     * Store a quick donation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:100000',
            'message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $amount = $request->amount;

        // Check if user has enough balance
        if ($user->wallet_balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'رصيد المحفظة غير كافي. رصيدك الحالي: ' . number_format($user->wallet_balance, 2) . ' ج.م'
            ], 400);
        }

        try {
            DB::beginTransaction();
            // Create general donation
            $donation = GeneralDonation::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == 1,
                'message' => $request->message,
                'payment_method' => 'wallet',
                'status' => 'completed'
            ]);

            // Deduct from wallet
            $user->wallet_balance -= $amount;
            $user->save();

            // Create wallet transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'description' => 'تبرع عام سريع',
                'reference_type' => 'general_donation',
                'reference_id' => $donation->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم التبرع بنجاح! شكراً لك على كرمك ❤️',
                'donation' => [
                    'amount' => number_format($amount, 2),
                    'new_balance' => number_format($user->wallet_balance, 2)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التبرع. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }
}
