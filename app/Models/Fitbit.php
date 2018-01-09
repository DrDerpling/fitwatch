<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use djchen\OAuth2\Client\Provider\Fitbit as FitbitProvider;

class Fitbit extends Model
{
    protected $fillable = [
        'fitbit_id',
        'access_token',
        'refresh_token',
        'active',
        'expire_date'
    ];

    public $dates = [
        'expire_date'
    ];

    public $provider;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->provider = new FitbitProvider([
            'clientId'          => config('fitbit.client.id'),
            'clientSecret'      => config('fitbit.client.secret'),
            'redirectUri'       => route('fitbitHook')
        ]);
    }

    /*
     * Relatisonship methode to user class
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorizationUrl()
    {
        return $this->provider->getAuthorizationUrl();
    }
}
