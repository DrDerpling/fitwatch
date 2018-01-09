<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $fitbit = $user->fitbit;
        if (!$fitbit) {
            $fitbit =  $user->fitbit()->create(['active' => 0]);
        }


        return view('home', compact('user', 'fitbit'));
    }
}
