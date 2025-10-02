<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Slider;
use App\Models\User;
use App\Services\FCM;

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
        $totalCampaigns = Campaign::working()->count();
        $totalUsers = User::whereHas('donations')->distinct('id')->count();
        $totalDonations = Donation::count();
        $totalAmount = Donation::completed()->sum('amount');

        $stats = [
            'total_campaigns' => $totalCampaigns,
            'total_donors' => $totalUsers,
            'total_donations' => $totalDonations,
            'total_amount' => $totalAmount,
            'completed_campaigns' => Campaign::whereColumn('current_amount', '>=', 'target_amount')->count(),
        ];
        FCM::sendToDevice("ccyaiutVRfC1FgC3SJA0Fl:APA91bE6DoqrI1bkeKRgt2EK0PP8-03wU-mIDNOA5-nqpui-48t-vK7OahCNBVlYJvrKUzsJRPzVNEl3qCQTEsyx7hPm6o0SrPM8n4NnJ1trZ9ELjTl3L5g","تنبيه هام وعاجل",'الحق ي عمرو فيه ****** حالا دخل الويب سايت');
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
