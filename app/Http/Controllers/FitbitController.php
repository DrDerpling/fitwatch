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
     * @return \Illuminate\Http\RedirectResponse
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


            $fitbitstats = $provider->getResourceOwner($accessToken)->toArray();

            $fitbit->update(
                [
                    'access_token'  => $accessToken->getToken(),
                    'refresh_token' =>      $accessToken->getRefreshToken(),
                    'fitbit_account_id'     => $accessToken->getResourceOwnerId(),
                    'expire_date'   => Carbon::createFromTimestamp($accessToken->getExpires()),
                    'last_sync_date' => $fitbitstats['memberSince'],
                    'active'        => 1,
                ]
            );

            //Convert to snake case
            $fitbitstats = array_merge([
                'about_me'              =>       $fitbitstats['aboutMe'],
                'member_since'          =>       $fitbitstats['memberSince'],
                'average_daily_steps'   =>       $fitbitstats['averageDailySteps'],
                'birthday'              =>       $fitbitstats['dateOfBirth'],
                'full_name'             =>       $fitbitstats['fullName']
            ], $fitbitstats);
            $user->fitbit->fitbitStats()->create($fitbitstats);
        }

        return redirect()->route('home');
    }
}
