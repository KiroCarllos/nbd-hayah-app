<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\UserFavoriteCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Campaign $campaign)
    {
        $user = Auth::user();

        // Check if already favorited
        $favorite = UserFavoriteCampaign::where('user_id', $user->id)
            ->where('campaign_id', $campaign->id)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'تم إزالة الحملة من المفضلة';
        } else {
            // Add to favorites
            UserFavoriteCampaign::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
            ]);
            $isFavorited = true;
            $message = 'تم إضافة الحملة للمفضلة';
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message,
            'favorites_count' => $campaign->favorites()->count()
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $favorites = UserFavoriteCampaign::where('user_id', $user->id)
            ->with('campaign')
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}
