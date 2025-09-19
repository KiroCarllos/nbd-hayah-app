<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralDonation;
use Illuminate\Http\Request;

class GeneralDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = GeneralDonation::with('user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by anonymous
        if ($request->filled('anonymous')) {
            $query->where('is_anonymous', $request->anonymous == '1');
        }

        // Search by user name or message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                })->orWhere('message', 'like', "%{$search}%");
            });
        }

        $donations = $query->paginate(20);

        // Statistics
        $stats = [
            'total_donations' => GeneralDonation::count(),
            'total_amount' => GeneralDonation::where('status', 'completed')->sum('amount'),
            'completed_donations' => GeneralDonation::where('status', 'completed')->count(),
            'pending_donations' => GeneralDonation::where('status', 'pending')->count(),
            'anonymous_donations' => GeneralDonation::where('is_anonymous', true)->count(),
        ];

        return view('admin.general-donations.index', compact('donations', 'stats'));
    }

    public function show(GeneralDonation $generalDonation)
    {
        // Load user relationship with donation counts
        $generalDonation->load(['user' => function ($query) {
            $query->withCount(['campaignDonations', 'generalDonations']);
        }]);

        // Get today's statistics
        $todayTotal = GeneralDonation::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('amount');

        $todayCount = GeneralDonation::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        $averageDonation = GeneralDonation::where('status', 'completed')
            ->avg('amount') ?? 0;

        return view('admin.general-donations.show', [
            'donation' => $generalDonation,
            'todayTotal' => $todayTotal,
            'todayCount' => $todayCount,
            'averageDonation' => $averageDonation
        ]);
    }
}
