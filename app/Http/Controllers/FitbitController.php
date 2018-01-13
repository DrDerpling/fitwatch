<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\FitbitSetup;

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
        $fitbit = $request->user()->fitbit;


        if ($code && $fitbit) {
            $fitbit->setup($code);
            return redirect()->route('fitbitSetup');
        } else {
            return abort(404);
        }
    }

    public function setup(Request $request)
    {
        $fitbit = $request->user()->fitbit;
        if ($request->user()->fitbit->active) {
            $fitbit->syncWeightLog();
            return view('pages.fitbit.setup', ['fitbit' => $request->user()->fitbit]);
        } else {
            return abort(404);
        }
    }
}
