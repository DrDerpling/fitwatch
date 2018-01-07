<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use djchen\OAuth2\Client\Provider\Fitbit as FitbitProvider;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Provider = new FitbitProvider([
            'clientId'          => config('fitbit.client.id'),
            'clientSecret'      => config('fitbit.client.secret'),
            'redirectUri'       => route('fitbitHook')
        ]);

        $authorizationUrl = $Provider->getAuthorizationUrl();

        return view('home', compact('authorizationUrl'));
    }
}
