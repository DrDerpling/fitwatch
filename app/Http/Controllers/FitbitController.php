<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp;
use App\Models\User;
use djchen\OAuth2\Client\Provider\Fitbit as FitbitProvider;

class FitbitController extends Controller
{
    /**
     * Stores resource in the database
     * @param Request $request
     */
    public function store(Request $request)
    {
        $code = \request('code');
        $user = $request->user();
        $fitbit = $user->fitbit;

        if ($code) {
            $provider = new FitbitProvider([
                'clientId'          => config('fitbit.client.id'),
                'clientSecret'      => config('fitbit.client.secret'),
                'redirectUri'       => route('fitbitHook')
            ]);

            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            $fitbit->update(
                [
                    'access_token' => $accessToken->getToken(),
                    'refresh_token' => $accessToken->getRefreshToken(),
                    'fitbit_id'     => $accessToken->getResourceOwnerId(),
                    'expire_date'   => Carbon::createFromTimestamp($accessToken->getExpires())
                ]
            );
        }
    }
}
