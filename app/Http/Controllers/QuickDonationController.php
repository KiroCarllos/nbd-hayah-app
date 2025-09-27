<?php

namespace App\Http\Controllers;

use App\Models\GeneralDonation;
use App\Models\WalletTransaction;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuickDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a quick donation
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:100000',
            'message' => 'nullable|string|max:500',
        ]);
        $user = Auth::user();
        $amount = $request->amount;
        $donationType = $request->donation_type;

        // Check if user has enough balance
        if ($user->wallet_balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'رصيد المحفظة غير كافي. رصيدك الحالي: ' . number_format($user->wallet_balance, 2) . ' ج.م'
            ], 400);
        }

        try {
            DB::beginTransaction();

            if (true) {
                $urgentCampaign = Campaign::query()
                    ->canAcceptDonations()
                    ->whereNotNull('end_date')
                    ->where('end_date', '>', now())
                    ->orderByRaw('(target_amount - current_amount) DESC')
                    ->orderBy('end_date', 'ASC')
                    ->first();

                if ($urgentCampaign) {
                    $donation = Donation::create([
                        'user_id' => $user->id,
                        'campaign_id' => $urgentCampaign->id,
                        'amount' => $amount,
                        'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == "on",
                        'status' => 'completed',
                        'transaction_reference' => 'URGENT_DON_' . $urgentCampaign->id . '_' . $user->id . '_' . time(),
                    ]);

                    $urgentCampaign->current_amount += $amount;
                    $urgentCampaign->save();

                    $donationMessage = 'تم التبرع بنجاح للحملة الأكثر احتياجاً: ' . $urgentCampaign->title;
                } else {
                    $donation = GeneralDonation::create([
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == "on",
                        'message' => $request->message,
                        'payment_method' => 'wallet',
                        'status' => 'completed'
                    ]);

                    $donationMessage = 'لا توجد حملات عاجلة حالياً. تم التبرع كتبرع عام.';
                }
            } else {
                // Create general donation
                $donation = GeneralDonation::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == "on",
                    'message' => $request->message,
                    'payment_method' => 'wallet',
                    'status' => 'completed'
                ]);

                $donationMessage = 'تم التبرع العام بنجاح! شكراً لك على كرمك ❤️';
            }

            // Deduct from wallet
            $user->wallet_balance -= $amount;
            $user->save();

            // Create wallet transaction
            $transactionDescription = $donationType === 'urgent' ? 'تبرع للحالات الأكثر احتياجاً' : 'تبرع عام سريع';
            $referenceType = $donationType === 'urgent' && isset($urgentCampaign) ? 'campaign_donation' : 'general_donation';

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'description' => $transactionDescription,
                'reference_type' => $referenceType,
                'reference_id' => $donation->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $donationMessage,
                'donation' => [
                    'amount' => number_format($amount, 2),
                    'new_balance' => number_format($user->wallet_balance, 2),
                    'type' => $donationType,
                    'campaign' => isset($urgentCampaign) ? $urgentCampaign->title : null
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
