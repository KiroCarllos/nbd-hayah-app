<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
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
        $stats = [
            'total_campaigns' => Campaign::active()->count(),
            'total_donors' => User::whereHas('donations')->count(),
            'total_donations' => Donation::completed()->sum('amount'),
            'completed_campaigns' => Campaign::whereColumn('current_amount', '>=', 'target_amount')->count(),
        ];
        return view('home', compact('priorityCampaigns', 'campaigns', 'stats'));
    }
}
