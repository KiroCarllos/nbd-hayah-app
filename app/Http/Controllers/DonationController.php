<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function donate(Request $request, Campaign $campaign)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'is_anonymous' => 'boolean',
        ], [
            'amount.required' => 'مبلغ التبرع مطلوب',
            'amount.numeric' => 'مبلغ التبرع يجب أن يكون رقماً',
            'amount.min' => 'الحد الأدنى للتبرع هو 1 ريال',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $isAnonymous = $request->boolean('is_anonymous');

        // Check if user has sufficient balance
        if ($user->wallet_balance < $amount) {
            return response()->json([
                'error' => 'رصيد المحفظة غير كافي. رصيدك الحالي: ' . number_format($user->wallet_balance, 2) . ' ر.س'
            ], 400);
        }

        // Check if campaign is active
        if (!$campaign->is_active) {
            return response()->json([
                'error' => 'هذه الحملة غير نشطة حالياً'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create donation record
            $donation = Donation::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'amount' => $amount,
                'is_anonymous' => $isAnonymous,
                'status' => 'completed',
                'transaction_reference' => 'DON_' . $campaign->id . '_' . $user->id . '_' . time(),
            ]);

            // Deduct from user wallet
            $user->wallet_balance -= $amount;
            $user->save();

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'description' => 'تبرع للحملة: ' . $campaign->title,
                'reference' => $donation->transaction_reference,
                'status' => 'completed',
                'payment_data' => [
                    'campaign_id' => $campaign->id,
                    'donation_id' => $donation->id,
                    'is_anonymous' => $isAnonymous,
                ],
            ]);

            // Update campaign current amount
            $campaign->current_amount += $amount;
            $campaign->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم التبرع بنجاح! شكراً لك على مساهمتك الكريمة.',
                'donation' => [
                    'amount' => number_format($amount, 2),
                    'campaign_progress' => $campaign->progress_percentage,
                    'new_balance' => number_format($user->wallet_balance, 2),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'حدث خطأ أثناء معالجة التبرع. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    public function myDonations()
    {
        $user = Auth::user();
        $donations = Donation::where('user_id', $user->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('donations.index', compact('donations'));
    }
}
