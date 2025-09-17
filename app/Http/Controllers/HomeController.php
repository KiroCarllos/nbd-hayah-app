<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Slider;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Get sliders
        $sliders = Slider::active()->ordered()->get();

        // Get priority campaigns for slider
        $priorityCampaigns = Campaign::active()
            ->priority()
            ->with(['creator', 'donations'])
            ->latest()
            ->take(5)
            ->get();

        // Get all active campaigns
        $campaigns = Campaign::active()
            ->with(['creator', 'donations'])
            ->latest()
            ->paginate(12);

        // Get statistics
        $totalCampaigns = Campaign::active()->count();
        $totalUsers = User::whereHas('donations')->count();
        $totalDonations = Donation::count();
        $totalAmount = Donation::completed()->sum('amount');

        $stats = [
            'total_campaigns' => $totalCampaigns,
            'total_donors' => $totalUsers,
            'total_donations' => $totalDonations,
            'total_amount' => $totalAmount,
            'completed_campaigns' => Campaign::whereColumn('current_amount', '>=', 'target_amount')->count(),
        ];

        return view('home', compact(
            'sliders',
            'priorityCampaigns',
            'campaigns',
            'stats',
            'totalCampaigns',
            'totalUsers',
            'totalDonations',
            'totalAmount'
        ));
    }
}
