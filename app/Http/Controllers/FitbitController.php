<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp;
use App\Models\User;
use djchen\OAuth2\Client\Provider\Fitbit as FitbitProvider;

class FitbitController extends Controller
{
    /**
     * Stores resource in the database
     * @param User $user
     */
    public function store(User $user)
    {
        $code = \request('code');

        if ($code) {
            $provider = new FitbitProvider([
                'clientId'          => config('fitbit.client.id'),
                'clientSecret'      => config('fitbit.client.secret'),
                'redirectUri'       => route('fitbitHook')
            ]);

            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

        }
    }
}
