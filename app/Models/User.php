<?php

namespace App\Models;

use App\FitbitStats;
use App\Models\Fitbit;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
     * Relationshop methode
     */
    public function fitbit()
    {
        return $this->hasOne(Fitbit::class);
    }

    public function fitbitStats()
    {
        return $this->hasOne(FitbitStats::class);
    }
}
