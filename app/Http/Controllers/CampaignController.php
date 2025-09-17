<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::active()
            ->with(['creator', 'donations'])
            ->latest()
            ->paginate(12);

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['creator', 'donations.user']);

        return view('campaigns.show', compact('campaign'));
    }

    public function toggleFavorite(Request $request, Campaign $campaign)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $user = auth()->user();

        if ($user->favoriteCampaigns()->where('campaign_id', $campaign->id)->exists()) {
            $user->favoriteCampaigns()->detach($campaign->id);
            $isFavorite = false;
        } else {
            $user->favoriteCampaigns()->attach($campaign->id);
            $isFavorite = true;
        }

        return response()->json(['is_favorite' => $isFavorite]);
    }
}
