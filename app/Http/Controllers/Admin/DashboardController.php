<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->is_admin) {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('is_active', true)->count(),
            'total_donations' => Donation::where('status', 'completed')->count(),
            'total_donated_amount' => Donation::where('status', 'completed')->sum('amount'),
            'total_wallet_balance' => User::sum('wallet_balance'),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'failed_donations' => Donation::where('status', 'failed')->count(),
        ];

        // Recent activities
        $recent_donations = Donation::with(['user', 'campaign'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_users = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_campaigns = Campaign::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly donation trends (last 6 months)
        $monthly_donations = Donation::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top campaigns by donations
        $top_campaigns = Campaign::withCount('donations')
            ->with(['creator'])
            ->orderBy('donations_count', 'desc')
            ->limit(5)
            ->get();

        // Top donors
        $top_donors = User::select('users.id', 'users.name', 'users.email') // حدد الأعمدة اللي محتاجها
            ->selectRaw('SUM(donations.amount) as total_donated')
            ->join('donations', 'users.id', '=', 'donations.user_id')
            ->where('donations.status', 'completed')
            ->where('users.is_admin', false)
            ->groupBy('users.id', 'users.name', 'users.email') // ضيف الأعمدة هنا
            ->orderBy('total_donated', 'desc')
            ->limit(5)
            ->get();


        return view('admin.dashboard', compact(
            'stats',
            'recent_donations',
            'recent_users',
            'recent_campaigns',
            'monthly_donations',
            'top_campaigns',
            'top_donors'
        ));
    }
}
