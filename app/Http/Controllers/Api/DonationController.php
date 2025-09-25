<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\WalletTransaction;
use App\Services\FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/donations",
     *     summary="الحصول على قائمة التبرعات للمستخدم",
     *     tags={"Donations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="رقم الصفحة",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة التبرعات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DonationResource"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $donations = auth()->user()->donations()
            ->with(['campaign.creator'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => DonationResource::collection($donations->items()),
            'meta' => [
                'current_page' => $donations->currentPage(),
                'last_page' => $donations->lastPage(),
                'per_page' => $donations->perPage(),
                'total' => $donations->total(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/campaigns/{id}/donate",
     *     summary="التبرع لحملة",
     *     tags={"Donations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف الحملة",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", format="float", example=100.00),
     *             @OA\Property(property="is_anonymous", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تم التبرع بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم التبرع بنجاح"),
     *             @OA\Property(property="data", ref="#/components/schemas/DonationResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="رصيد غير كافي أو حملة غير نشطة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="رصيد غير كافي")
     *         )
     *     )
     * )
     */
    public function donate(Request $request, $campaignId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'is_anonymous' => 'boolean',
        ], [
            'amount.required' => 'مبلغ التبرع مطلوب',
            'amount.numeric' => 'مبلغ التبرع يجب أن يكون رقم',
            'amount.min' => 'مبلغ التبرع يجب أن يكون أكبر من صفر',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $campaign = Campaign::find($campaignId);
        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'الحملة غير موجودة'
            ], 404);
        }

        // Check if campaign can accept donations
        if (!$campaign->canAcceptDonations()) {
            $message = 'لا يمكن التبرع لهذه الحملة. ';

            if (!$campaign->is_active) {
                $message .= 'الحملة غير نشطة حالياً.';
            } elseif ($campaign->isCompleted()) {
                $message .= 'تم الوصول للهدف المطلوب.';
            } elseif ($campaign->hasEnded()) {
                $message .= 'انتهت فترة التبرع لهذه الحملة.';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        $user = auth()->user();
        $amount = $request->amount;

        if ($user->wallet_balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'رصيد غير كافي'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create donation
            $donation = Donation::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'amount' => $amount,
                'is_anonymous' => $request->get('is_anonymous', false),
                'status' => 'completed',
                'payment_method' => 'wallet',
                'transaction_reference' => 'DON_' . $campaign->id . '_' . $user->id . '_' . time(),
            ]);

            // Update user wallet
            $user->decrement('wallet_balance', $amount);

            // Update campaign amount
            $campaign->increment('current_amount', $amount);

            DB::commit();

            $donation->load(['campaign.creator']);

            $fcm = FCM::sendToDevice(
                $user->device_token,
                'تم التبرع بنجاح 💚',
                "شكرًا لأنك كنت سببًا في إنقاذ حياة شخص ما اليوم."
            );

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
                    'is_anonymous' => $request->get('is_anonymous', false),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم التبرع بنجاح',
                'data' => new DonationResource($donation)
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التبرع'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/donations/{id}",
     *     summary="الحصول على تفاصيل تبرع",
     *     tags={"Donations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف التبرع",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تفاصيل التبرع",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/DonationResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="التبرع غير موجود",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="التبرع غير موجود")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $donation = auth()->user()->donations()
            ->with(['campaign.creator'])
            ->find($id);

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'التبرع غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new DonationResource($donation)
        ]);
    }
}
