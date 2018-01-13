<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    protected $fillable = [
        'weight'
    ];

    public $dates = [
        'log_date'
    ];

    public $timestamps = false;
}
