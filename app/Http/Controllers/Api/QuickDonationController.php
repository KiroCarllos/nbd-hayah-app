<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralDonation;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuickDonationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/urgent-campaigns",
     *     summary="الحصول على الحملات الأكثر احتياجاً",
     *     tags={"Quick Donations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="قائمة الحملات الأكثر احتياجاً",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="urgent_campaign", type="object", nullable=true,
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="اسم الحملة"),
     *                     @OA\Property(property="description", type="string", example="وصف الحملة"),
     *                     @OA\Property(property="target_amount", type="number", example=10000.00),
     *                     @OA\Property(property="current_amount", type="number", example=7500.00),
     *                     @OA\Property(property="remaining_amount", type="number", example=2500.00),
     *                     @OA\Property(property="progress_percentage", type="number", example=75.0),
     *                     @OA\Property(property="end_date", type="string", format="date", example="2024-12-31"),
     *                     @OA\Property(property="days_remaining", type="integer", example=15)
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Get the most urgent campaign information
     */
    public function getUrgentCampaign()
    {
        $urgentCampaign = Campaign::canAcceptDonations()
            ->whereNotNull('end_date')
            ->where('end_date', '>', now())
            ->orderByRaw('(target_amount - current_amount) DESC')
            ->orderBy('end_date', 'ASC')
            ->first();

        if ($urgentCampaign) {
            $remainingAmount = $urgentCampaign->target_amount - $urgentCampaign->current_amount;
            $progressPercentage = ($urgentCampaign->current_amount / $urgentCampaign->target_amount) * 100;
            $daysRemaining = now()->diffInDays($urgentCampaign->end_date);

            return response()->json([
                'success' => true,
                'data' => [
                    'urgent_campaign' => [
                        'id' => $urgentCampaign->id,
                        'title' => $urgentCampaign->title,
                        'description' => $urgentCampaign->description,
                        'target_amount' => $urgentCampaign->target_amount,
                        'current_amount' => $urgentCampaign->current_amount,
                        'remaining_amount' => $remainingAmount,
                        'progress_percentage' => round($progressPercentage, 2),
                        'end_date' => $urgentCampaign->end_date->format('Y-m-d'),
                        'days_remaining' => $daysRemaining
                    ]
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'urgent_campaign' => null
            ]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/quick-donate",
     *     summary="التبرع السريع",
     *     tags={"Quick Donations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount", "donation_type"},
     *             @OA\Property(property="amount", type="number", format="float", example=100.00, description="مبلغ التبرع"),
     *             @OA\Property(property="donation_type", type="string", enum={"general", "urgent"}, example="general", description="نوع التبرع: عام أو للحالات الأكثر احتياجاً"),
     *             @OA\Property(property="message", type="string", example="رسالة تشجيعية", description="رسالة اختيارية"),
     *             @OA\Property(property="is_anonymous", type="boolean", example=false, description="تبرع مجهول")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم التبرع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التبرع بنجاح للحملة الأكثر احتياجاً: اسم الحملة"),
     *             @OA\Property(property="donation", type="object",
     *                 @OA\Property(property="amount", type="string", example="100.00"),
     *                 @OA\Property(property="new_balance", type="string", example="500.00"),
     *                 @OA\Property(property="type", type="string", example="urgent"),
     *                 @OA\Property(property="campaign", type="object", nullable=true,
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="اسم الحملة"),
     *                     @OA\Property(property="remaining_amount", type="number", example=500.00)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="رصيد غير كافي",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="رصيد المحفظة غير كافي")
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
     *
     * Store a quick donation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:500',
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
                        'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == 1,
                        'status' => 'completed',
                        'transaction_reference' => 'URGENT_DON_' . $urgentCampaign->id . '_' . $user->id . '_' . time(),
                    ]);

                    $urgentCampaign->current_amount += $amount;
                    $urgentCampaign->save();

                    // Check if campaign just completed
                    $campaignJustCompleted = $urgentCampaign->isCompleted();

                    $donationMessage = 'تم التبرع بنجاح للحملة الأكثر احتياجاً: ' . $urgentCampaign->title;

                    // If campaign just completed, notify all donors
                    if ($campaignJustCompleted) {
                        $this->notifyDonorsOnCampaignCompletion($urgentCampaign);
                    }
                } else {
                    // No urgent campaigns found, create general donation
                    $donation = GeneralDonation::create([
                        'user_id' => $user->id,
                        'amount' => $amount,
                        'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == 1,
                        'message' => $request->message,
                        'payment_method' => 'wallet',
                        'status' => 'completed'
                    ]);

                    $donationMessage = 'لا توجد حملات عاجلة حالياً. تم التبرع كتبرع عام.';
                }
            } else {
                $donation = GeneralDonation::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'is_anonymous' => $request->has('is_anonymous') && $request->is_anonymous == 1,
                    'message' => $request->message,
                    'payment_method' => 'wallet',
                    'status' => 'completed'
                ]);

                $donationMessage = 'تم التبرع العام بنجاح! شكراً لك على كرمك ❤️';
            }

            // Deduct from wallet
            $user->wallet_balance -= $amount;
            $user->save();

            // Send FCM notification
            if ($user->device_token) {
                FCM::sendToDevice(
                    $user->device_token,
                    'تم التبرع بنجاح 💚',
                    $donationMessage
                );
            }

            // Create wallet transaction
            $transactionDescription = true ? 'تبرع للحالات الأكثر احتياجاً' : 'تبرع عام سريع';
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
                    'campaign' => isset($urgentCampaign) ? [
                        'id' => $urgentCampaign->id,
                        'title' => $urgentCampaign->title,
                        'remaining_amount' => $urgentCampaign->target_amount - $urgentCampaign->current_amount
                    ] : null
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

    /**
     * Send completion notifications to all campaign donors
     *
     * @param Campaign $campaign
     * @return void
     */
    protected function notifyDonorsOnCampaignCompletion(Campaign $campaign)
    {
        try {
            // Get all unique donors for this campaign with their device tokens
            $donors = User::whereHas('donations', function ($query) use ($campaign) {
                $query->where('campaign_id', $campaign->id)
                    ->where('status', 'completed');
            })
                ->whereNotNull('device_token')
                ->where('device_token', '!=', '')
                ->get();

            // Prepare notification message
            $title = '🎉 تم اكتمال الحملة!';
            $body = "الحمد لله! تم اكتمال حملة \"{$campaign->title}\" بفضل تبرعك الكريم. شكراً لك على مساهمتك في إنقاذ حياة! ❤️";

            $notificationData = [
                'campaign_id' => (string)$campaign->id,
                'campaign_title' => $campaign->title,
                'type' => 'campaign_completed',
                'completed_at' => now()->toDateTimeString(),
            ];

            // Send notification to each donor
            foreach ($donors as $donor) {
                FCM::sendToDevice(
                    $donor->device_token,
                    $title,
                    $body,
                    $notificationData
                );
            }

            // Log the notification
            Log::info("Campaign completion notifications sent", [
                'campaign_id' => $campaign->id,
                'campaign_title' => $campaign->title,
                'donors_count' => $donors->count(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the donation process
            Log::error("Failed to send campaign completion notifications", [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
