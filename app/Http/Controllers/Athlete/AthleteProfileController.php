<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AthleteProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $athlete = $user->athlete()->with('clubs')->first();

        return view('athlete.profile', compact('user', 'athlete'));
    }
}
