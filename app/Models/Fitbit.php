<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\WeightLog;
use djchen\OAuth2\Client\Provider\Fitbit as FitbitProvider;
use Illuminate\Support\Carbon;
use GuzzleHttp;
use GuzzleHttp\Psr7\Request as GuzzleRequest;


class Fitbit extends Model
{
    protected $fillable = [
        'fitbit_account_id',
        'access_token',
        'refresh_token',
        'active',
        'expire_date',
        'last_sync_date'
    ];

    public $dates = [
        'expire_date',
        'last_sync_date'
    ];

    public $provider;
    public $GuzzleClient;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $redirectUri = isset($attributes['$attributes'])? $attributes['$attributes'] : route('fitbitHook');
        $this->provider = new FitbitProvider([
            'clientId'          => config('fitbit.client.id'),
            'clientSecret'      => config('fitbit.client.secret'),
            'redirectUri'       => $redirectUri
        ]);
        $this->GuzzleClient = new GuzzleHttp\client();
    }

    /*
     * Relatisonship methode with user class
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship method with fitbitStats class
     */
    public function fitbitStats()
    {
        return $this->hasOne(FitbitStats::class);
    }

    /**
     * Relationship method with WeightLog class
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function weightLog()
    {
        return $this->hasMany(WeightLog::class);
    }

    /**
     * Relationship method with Activities class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(Activities::class, 'activitieble');
    }

    /**
     * Relationship method with Tracker class
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracker()
    {
        return $this->hasMany(Tracker::class);
    }

    /**
     * return url for fitbit authorization
     *
     * @return string
     */
    public function authorizationUrl()
    {
        return $this->provider->getAuthorizationUrl();
    }

    /**
     * Setup fitbit account
     *
     * @param $token
     */
    public function setup($token)
    {
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $token
        ]);
        $fitbitStats = $this->provider->getResourceOwner($accessToken)->toArray();

        $this->update(
            [
                'access_token'  => $accessToken->getToken(),
                'refresh_token' =>      $accessToken->getRefreshToken(),
                'fitbit_account_id'     => $accessToken->getResourceOwnerId(),
                'expire_date'   => Carbon::createFromTimestamp($accessToken->getExpires()),
                'last_sync_date' => $fitbitStats['memberSince'],
                'active'        => 1,
            ]
        );
        $fitbitStats = array_merge([
            'about_me'              =>       $fitbitStats['aboutMe'],
            'member_since'          =>       $fitbitStats['memberSince'],
            'average_daily_steps'   =>       $fitbitStats['averageDailySteps'],
            'birthday'              =>       $fitbitStats['dateOfBirth'],
            'full_name'             =>       $fitbitStats['fullName']
        ], $fitbitStats);
        $this->fitbitStats()->create($fitbitStats);
    }
    /*
     * Syncs the weight log with the fitbit API
     */
    public function syncWeightLog()
    {
        $url = $this->getResourcePath('weight');
        $request = $this->GuzzleClient->request(
            'GET',
            $url,
            ['headers' =>[
                'Authorization' => 'Bearer ' .$this->access_token
            ]]
        );

        $weightlog = $request->getBody()->getContents();
        $weightlog = json_decode($weightlog, true);
        $weightlog = collect($weightlog['body-weight']);
        return $weightlog = $weightlog->map(function ($log) {
            $newlog['weight'] = $log['value'];
            $newlog['fitbit_id'] = $this->id;
            $newlog['log_date'] = $log['dateTime'];
            unset($log);
            return $newlog;
        });

        WeightLog::insert($weightlog->toArray());
    }
    public function syncActivities()
    {
        $paths = $this->getResourcePaths('activities');
        $requests = [];

        foreach ($paths as $path) {
            $requests[] = $this->GuzzleClient->get($path, $this->setupGuzzleHeader());
        }
        dd($requests[0]->getBody()->getContents());
    }

    /**
     * @param $resource
     * @return static
     */
    private function getResourcePaths($resource)
    {
        switch ($resource) {
            case 'activities':
                $url = 'https://api.fitbit.com/1/user/'.
                    $this->fitbit_account_id.
                    '/';
                $resourcePaths = collect([
                    'calories',
                    'caloriesBMR',
                    'steps',
                    'distance',
                    'floors',
                    'elevation',
                    'minutesSedentary',
                    'minutesLightlyActive',
                    'minutesFairlyActive',
                    'minutesVeryActive',
//                    'activityCalories'
                ]);
                return  $resourcePaths->map(function ($path) use ($url) {

                        $newPath = $this->getBaseUrl().
                            '/activities/'.$path.
                            '/date/'.
                            $this->last_sync_date->format('Y-m-d').'/'.Carbon::now()->format('Y-m-d').'.json';
                        unset($path);
                        return $newPath;
                })->toArray();
                break;
            default:
                return false;
                break;
        }
    }
    private function getResourcePath($resource)
    {
        switch ($resource) {
            case 'weight':
                return $URL = 'https://api.fitbit.com/1/user/'.
                    $this->fitbit_account_id.
                    '/body/weight/date/'.
                    $this->last_sync_date->format('Y-m-d').
                    '/'.
                    now()->format('Y-m-d').
                    '.json';
                break;
            default:
                return false;
                break;
        }
    }
    private function getBaseUrl()
    {
        return 'https://api.fitbit.com/1/user/'.$this->fitbit_account_id;
    }
    private function setupGuzzleHeader()
    {
        return  ['headers' => ['Authorization' => 'Bearer ' .$this->access_token ]];
    }
}
