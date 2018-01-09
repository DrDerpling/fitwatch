<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function weightSync(Request $request)
    {
        $user = $request->user();

        dd($user->fibit)
    }
}
