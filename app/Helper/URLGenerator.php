<?php

namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use AshAllenDesign\ShortURL\Facades\ShortURL;

class URLGenerator
{
    public static function generateSignedUrl(int $userId, int $validityMinutes = 60)
    {
        $user = User::where('id', $userId)->first();
        $token = Str::random(60);
        $user->auto_login_token = $token;
        $user->auto_login_token_expires_at = now()->addMinutes($validityMinutes);
        $user->save();

        // Generate Signed URL
        return URL::temporarySignedRoute(
            'auto.login'
            , now()->addMinutes(10)
            , [
                'token' => $token
            ]
        );
    }

    public static function shortenUrl($autoSigninUrl)
    {
        // Shorten the URL
        $shortUrlObj = ShortURL::destinationUrl($autoSigninUrl)->make();
        return $shortUrlObj->default_short_url;
    }

    public static function generateAndShortenSignedUrl(int $userId, int $validityMinutes = 60)
    {
        return self::shortenUrl(self::generateSignedUrl($userId, $validityMinutes));
    }
}
