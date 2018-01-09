<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitbitStats extends Model
{
    /*
     * $fillable
     */
    protected $fillable = [
        'age',
        'height',
        'average_daily_steps',
        'weight',
        'birthday',
        'gender',
        'avatar',
        'about_me',
        'member_since',
        'full_name'
    ];

    /*
     * Relatsionship methode with fitbit class
     */
    public function fibit()
    {
        return $this->belongsTo(Fitbit::class);
    }
}
