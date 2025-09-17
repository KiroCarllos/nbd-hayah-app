<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::active()->count();
        $totalUsers = User::count();
        $totalDonations = Donation::completed()->count();
        $totalAmount = Donation::completed()->sum('amount');
        $totalWalletBalance = User::sum('wallet_balance');

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
            ->get();

        $topCampaigns = Campaign::withCount('donations')
            ->with('creator')
            ->orderBy('donations_count', 'desc')
            ->limit(10)
            ->get();

        $topDonors = User::select('users.id', 'users.name', 'users.email') // الأعمدة المطلوبة فقط
            ->selectRaw('COALESCE(COUNT(donations.id), 0) as donations_count')
            ->selectRaw('COALESCE(SUM(donations.amount), 0) as total_donated')
            ->leftJoin('donations', function ($join) {
                $join->on('users.id', '=', 'donations.user_id')
                    ->where('donations.status', 'completed');
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get();


        $dailyDonations = Donation::completed()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $campaignStats = [
            'active' => Campaign::active()->count(),
            'inactive' => Campaign::where('is_active', false)->count(),
            'priority' => Campaign::where('is_priority', true)->count(),
            'completed' => Campaign::whereRaw('current_amount >= target_amount')->count(),
        ];

        $walletTransactions = collect([
            // All donations (since they use wallet balance)
            (object) [
                'type' => 'donation',
                'type_ar' => 'تبرعات',
                'count' => Donation::completed()->count(),
                'total' => Donation::completed()->sum('amount'),
                'icon' => 'fas fa-heart',
                'color' => 'text-danger'
            ],
            // Wallet charges (credit transactions)
            (object) [
                'type' => 'charge',
                'type_ar' => 'شحن محفظة',
                'count' => \App\Models\WalletTransaction::where('type', 'credit')->count(),
                'total' => \App\Models\WalletTransaction::where('type', 'credit')->sum('amount'),
                'icon' => 'fas fa-plus-circle',
                'color' => 'text-success'
            ],
            // Wallet debits (money spent from wallet)
            (object) [
                'type' => 'debit',
                'type_ar' => 'سحب من المحفظة',
                'count' => \App\Models\WalletTransaction::where('type', 'debit')->count(),
                'total' => \App\Models\WalletTransaction::where('type', 'debit')->sum('amount'),
                'icon' => 'fas fa-minus-circle',
                'color' => 'text-warning'
            ]
        ]);

        // Anonymous vs Public donations
        $anonymityStats = [
            'anonymous' => Donation::completed()->where('is_anonymous', true)->count(),
            'public' => Donation::completed()->where('is_anonymous', false)->count(),
        ];

        return view('admin.reports.index', compact(
            'totalCampaigns',
            'activeCampaigns',
            'totalUsers',
            'totalDonations',
            'totalAmount',
            'totalWalletBalance',
            'monthlyDonations',
            'topCampaigns',
            'topDonors',
            'dailyDonations',
            'campaignStats',
            'walletTransactions',
            'anonymityStats'
        ));
    }

    public function campaignDetails($id)
    {
        $campaign = Campaign::with(['creator', 'donations.user'])->findOrFail($id);

        $campaignDonations = $campaign->donations()
            ->completed()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $campaignTopDonors = User::select('users.*')
            ->selectRaw('COUNT(donations.id) as donations_count')
            ->selectRaw('SUM(donations.amount) as total_donated')
            ->join('donations', 'users.id', '=', 'donations.user_id')
            ->where('donations.campaign_id', $id)
            ->where('donations.status', 'completed')
            ->where('donations.is_anonymous', false)
            ->groupBy('users.id')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.campaign-details', compact(
            'campaign',
            'campaignDonations',
            'campaignTopDonors'
        ));
    }

    public function userDetails($id)
    {
        $user = User::with(['donations.campaign'])->findOrFail($id);

        $userDonations = $user->donations()
            ->completed()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $favoriteCampaigns = $user->favoriteCampaigns()
            ->withCount('donations')
            ->get();

        return view('admin.reports.user-details', compact(
            'user',
            'userDonations',
            'favoriteCampaigns'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'donations');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'donations':
                return $this->exportDonations($format);
            case 'campaigns':
                return $this->exportCampaigns($format);
            case 'users':
                return $this->exportUsers($format);
            default:
                return redirect()->back()->with('error', 'نوع التصدير غير صحيح');
        }
    }

    private function exportDonations($format)
    {
        $donations = Donation::with(['user', 'campaign'])
            ->completed()
            ->latest()
            ->get();

        $filename = 'donations_' . date('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $callback = function () use ($donations) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8 encoding to support Arabic
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Use UTF-8 encoding for Arabic headers
                fputcsv($file, ['ID', 'المتبرع', 'الحملة', 'المبلغ', 'مجهول', 'التاريخ']);

                foreach ($donations as $donation) {
                    fputcsv($file, [
                        $donation->id,
                        $donation->is_anonymous ? 'متبرع مجهول' : $donation->user->name,
                        $donation->campaign->title,
                        number_format($donation->amount, 2) . ' ج.م',
                        $donation->is_anonymous ? 'نعم' : 'لا',
                        $donation->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'تنسيق التصدير غير مدعوم');
    }

    private function exportCampaigns($format)
    {
        $campaigns = Campaign::with(['creator'])
            ->withCount('donations')
            ->get();

        $filename = 'campaigns_' . date('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $callback = function () use ($campaigns) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8 encoding to support Arabic
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($file, ['ID', 'العنوان', 'المنشئ', 'الهدف', 'المحصل', 'النسبة', 'التبرعات', 'الحالة', 'التاريخ']);

                foreach ($campaigns as $campaign) {
                    fputcsv($file, [
                        $campaign->id,
                        $campaign->title,
                        $campaign->creator ? $campaign->creator->name : 'غير محدد',
                        number_format($campaign->target_amount, 2) . ' ج.م',
                        number_format($campaign->current_amount, 2) . ' ج.م',
                        round($campaign->progress_percentage, 2) . '%',
                        $campaign->donations_count,
                        $campaign->is_active ? 'نشطة' : 'غير نشطة',
                        $campaign->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'تنسيق التصدير غير مدعوم');
    }

    private function exportUsers($format)
    {
        $users = User::select(
            'users.id',
            'users.name',
            'users.mobile',
            'users.wallet_balance',
            'users.is_admin',
            'users.created_at'
        )
            ->selectRaw('COUNT(donations.id) as donations_count')
            ->selectRaw('COALESCE(SUM(donations.amount), 0) as total_donated')
            ->leftJoin('donations', function ($join) {
                $join->on('users.id', '=', 'donations.user_id')
                    ->where('donations.status', '=', 'completed');
            })
            ->groupBy(
                'users.id',
                'users.name',
                'users.mobile',
                'users.wallet_balance',
                'users.is_admin',
                'users.created_at'
            )
            ->get();

        $filename = 'users_' . date('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            $callback = function () use ($users) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8 encoding to support Arabic
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Header row
                fputcsv($file, [
                    'ID',
                    'الاسم',
                    'الهاتف',
                    'رصيد المحفظة',
                    'إجمالي التبرعات',
                    'عدد التبرعات',
                    'أدمن',
                    'تاريخ التسجيل'
                ]);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->mobile,
                        number_format($user->wallet_balance, 2) . ' ج.م',
                        number_format($user->total_donated, 2) . ' ج.م',
                        $user->donations_count,
                        $user->is_admin ? 'نعم' : 'لا',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'تنسيق التصدير غير مدعوم');
    }
}
