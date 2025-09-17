<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/reports/dashboard",
     *     summary="إحصائيات لوحة التحكم",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="إحصائيات شاملة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="overview", type="object",
     *                     @OA\Property(property="total_campaigns", type="integer", example=150),
     *                     @OA\Property(property="active_campaigns", type="integer", example=120),
     *                     @OA\Property(property="total_users", type="integer", example=500),
     *                     @OA\Property(property="total_donations", type="integer", example=1200),
     *                     @OA\Property(property="total_amount", type="number", format="float", example=250000.00),
     *                     @OA\Property(property="total_wallet_balance", type="number", format="float", example=50000.00)
     *                 ),
     *                 @OA\Property(property="monthly_donations", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="month", type="string", example="2023-01"),
     *                     @OA\Property(property="count", type="integer", example=45),
     *                     @OA\Property(property="total", type="number", format="float", example=12500.00)
     *                 )),
     *                 @OA\Property(property="campaign_stats", type="object",
     *                     @OA\Property(property="active", type="integer", example=120),
     *                     @OA\Property(property="inactive", type="integer", example=30),
     *                     @OA\Property(property="priority", type="integer", example=15),
     *                     @OA\Property(property="completed", type="integer", example=85)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function dashboard()
    {
        // Basic statistics
        $overview = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::active()->count(),
            'total_users' => User::count(),
            'total_donations' => Donation::completed()->count(),
            'total_amount' => (float) Donation::completed()->sum('amount'),
            'total_wallet_balance' => (float) User::sum('wallet_balance'),
        ];

        // Monthly donations for the last 12 months
        $monthlyDonations = Donation::completed()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => sprintf('%04d-%02d', $item->year, $item->month),
                    'count' => $item->count,
                    'total' => (float) $item->total,
                ];
            });

        // Campaign status distribution
        $campaignStats = [
            'active' => Campaign::active()->count(),
            'inactive' => Campaign::where('is_active', false)->count(),
            'priority' => Campaign::where('is_priority', true)->count(),
            'completed' => Campaign::whereRaw('current_amount >= target_amount')->count(),
        ];

        // Daily donations for the last 30 days
        $dailyDonations = Donation::completed()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                    'total' => (float) $item->total,
                ];
            });

        // Anonymous vs Public donations
        $anonymityStats = [
            'anonymous' => Donation::completed()->where('is_anonymous', true)->count(),
            'public' => Donation::completed()->where('is_anonymous', false)->count(),
        ];

        // Wallet transactions statistics
        $walletTransactions = [
            [
                'type' => 'donation',
                'type_ar' => 'تبرعات',
                'count' => Donation::completed()->count(),
                'total' => (float) Donation::completed()->sum('amount'),
                'icon' => 'fas fa-heart',
                'color' => 'text-danger'
            ],
            [
                'type' => 'charge',
                'type_ar' => 'شحن محفظة',
                'count' => \App\Models\WalletTransaction::where('type', 'credit')->count(),
                'total' => (float) \App\Models\WalletTransaction::where('type', 'credit')->sum('amount'),
                'icon' => 'fas fa-plus-circle',
                'color' => 'text-success'
            ],
            [
                'type' => 'debit',
                'type_ar' => 'سحب من المحفظة',
                'count' => \App\Models\WalletTransaction::where('type', 'debit')->count(),
                'total' => (float) \App\Models\WalletTransaction::where('type', 'debit')->sum('amount'),
                'icon' => 'fas fa-minus-circle',
                'color' => 'text-warning'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => $overview,
                'monthly_donations' => $monthlyDonations,
                'daily_donations' => $dailyDonations,
                'campaign_stats' => $campaignStats,
                'anonymity_stats' => $anonymityStats,
                'wallet_transactions' => $walletTransactions,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/reports/top-campaigns",
     *     summary="أفضل الحملات",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="عدد الحملات",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="أفضل الحملات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="مساعدة الأسر المحتاجة"),
     *                 @OA\Property(property="current_amount", type="number", format="float", example=15000.00),
     *                 @OA\Property(property="target_amount", type="number", format="float", example=50000.00),
     *                 @OA\Property(property="donations_count", type="integer", example=45),
     *                 @OA\Property(property="progress_percentage", type="number", format="float", example=30.0),
     *                 @OA\Property(property="creator", type="object",
     *                     @OA\Property(property="name", type="string", example="أحمد محمد")
     *                 )
     *             ))
     *         )
     *     )
     * )
     */
    public function topCampaigns(Request $request)
    {
        $limit = $request->get('limit', 10);

        $campaigns = Campaign::withCount('donations')
            ->with('creator:id,name')
            ->orderBy('donations_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'current_amount' => (float) $campaign->current_amount,
                    'target_amount' => (float) $campaign->target_amount,
                    'donations_count' => $campaign->donations_count,
                    'progress_percentage' => (float) $campaign->progress_percentage,
                    'creator' => [
                        'name' => $campaign->creator->name ?? 'غير محدد'
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $campaigns
        ]);
    }

    /**
     * @OA\Get(
     *     path="/reports/top-donors",
     *     summary="أفضل المتبرعين",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="عدد المتبرعين",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="أفضل المتبرعين",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="أحمد محمد"),
     *                 @OA\Property(property="donations_count", type="integer", example=25),
     *                 @OA\Property(property="total_donated", type="number", format="float", example=5000.00)
     *             ))
     *         )
     *     )
     * )
     */
    public function topDonors(Request $request)
    {
        $limit = $request->get('limit', 10);

        $donors = User::select('users.id', 'users.name')
            ->selectRaw('COUNT(donations.id) as donations_count')
            ->selectRaw('SUM(donations.amount) as total_donated')
            ->leftJoin('donations', function ($join) {
                $join->on('users.id', '=', 'donations.user_id')
                    ->where('donations.status', '=', 'completed')
                    ->where('donations.is_anonymous', '=', false);
            })
            ->groupBy('users.id', 'users.name')
            ->having('donations_count', '>', 0)
            ->orderBy('total_donated', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'donations_count' => $user->donations_count,
                    'total_donated' => (float) $user->total_donated,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $donors
        ]);
    }

    /**
     * @OA\Get(
     *     path="/reports/campaign/{id}",
     *     summary="تقرير تفصيلي لحملة",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="معرف الحملة",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تقرير الحملة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="campaign", type="object"),
     *                 @OA\Property(property="donations_timeline", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="date", type="string", example="2023-01-01"),
     *                     @OA\Property(property="count", type="integer", example=5),
     *                     @OA\Property(property="total", type="number", format="float", example=1500.00)
     *                 )),
     *                 @OA\Property(property="top_donors", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="أحمد محمد"),
     *                     @OA\Property(property="donations_count", type="integer", example=3),
     *                     @OA\Property(property="total_donated", type="number", format="float", example=750.00)
     *                 ))
     *             )
     *         )
     *     )
     * )
     */
    public function campaignReport($id)
    {
        $campaign = Campaign::with('creator:id,name')->findOrFail($id);

        // Campaign donations over time
        $donationsTimeline = $campaign->donations()
            ->completed()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                    'total' => (float) $item->total,
                ];
            });

        // Top donors for this campaign
        $topDonors = User::select('users.id', 'users.name')
            ->selectRaw('COUNT(donations.id) as donations_count')
            ->selectRaw('SUM(donations.amount) as total_donated')
            ->join('donations', 'users.id', '=', 'donations.user_id')
            ->where('donations.campaign_id', $id)
            ->where('donations.status', 'completed')
            ->where('donations.is_anonymous', false)
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'donations_count' => $user->donations_count,
                    'total_donated' => (float) $user->total_donated,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'campaign' => [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'target_amount' => (float) $campaign->target_amount,
                    'current_amount' => (float) $campaign->current_amount,
                    'progress_percentage' => (float) $campaign->progress_percentage,
                    'creator' => [
                        'name' => $campaign->creator->name ?? 'غير محدد'
                    ],
                    'created_at' => $campaign->created_at->toISOString(),
                ],
                'donations_timeline' => $donationsTimeline,
                'top_donors' => $topDonors,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/reports/user-stats",
     *     summary="إحصائيات المستخدم الحالي",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="إحصائيات المستخدم",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_donated", type="number", format="float", example=1500.00),
     *                 @OA\Property(property="donations_count", type="integer", example=12),
     *                 @OA\Property(property="favorite_campaigns_count", type="integer", example=5),
     *                 @OA\Property(property="created_campaigns_count", type="integer", example=2),
     *                 @OA\Property(property="wallet_balance", type="number", format="float", example=250.00),
     *                 @OA\Property(property="monthly_donations", type="array", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="month", type="string", example="2023-01"),
     *                     @OA\Property(property="count", type="integer", example=3),
     *                     @OA\Property(property="total", type="number", format="float", example=450.00)
     *                 ))
     *             )
     *         )
     *     )
     * )
     */
    public function userStats()
    {
        $user = auth()->user();

        // User's donation statistics
        $totalDonated = $user->donations()->completed()->sum('amount');
        $donationsCount = $user->donations()->completed()->count();
        $favoriteCampaignsCount = $user->favorites()->count();
        $createdCampaignsCount = Campaign::where('created_by', $user->id)->count();

        // Monthly donations for the last 6 months
        $monthlyDonations = $user->donations()
            ->completed()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => sprintf('%04d-%02d', $item->year, $item->month),
                    'count' => $item->count,
                    'total' => (float) $item->total,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_donated' => (float) $totalDonated,
                'donations_count' => $donationsCount,
                'favorite_campaigns_count' => $favoriteCampaignsCount,
                'created_campaigns_count' => $createdCampaignsCount,
                'wallet_balance' => (float) $user->wallet_balance,
                'monthly_donations' => $monthlyDonations,
            ]
        ]);
    }
}
