<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getUserProfileImage($user)
    {
        if (!$user || !$user->profile_image) {
            return asset('default.png');
        }

        if ($user->profile_image === 'default.png') {
            return asset('default.png');
        }

        if (str_starts_with($user->profile_image, 'profile_images/')) {
            $imagePath = storage_path('app/public/' . $user->profile_image);
            if (file_exists($imagePath)) {
                return asset('storage/' . $user->profile_image);
            }
        }

        return asset('default.png');
    }

    public static function getAnonymousImage()
    {
        return asset('secret.jpg');
    }


    public static function getDonationUserImage($donation)
    {
        if ($donation->is_anonymous) {
            return self::getAnonymousImage();
        }

        return self::getUserProfileImage($donation->user);
    }


    public static function getCampaignImage($campaign)
    {
        if (!$campaign || !$campaign->images || $campaign->images->isEmpty()) {
            return asset('default.png');
        }

        $firstImage = $campaign->images->first();
        if (!$firstImage || !$firstImage->image_path) {
            return asset('default.png');
        }

        if (str_starts_with($firstImage->image_path, 'campaign_images/')) {
            $imagePath = storage_path('app/public/' . $firstImage->image_path);
            if (file_exists($imagePath)) {
                return asset('storage/' . $firstImage->image_path);
            }
        }

        return asset('default.png');
    }
}
