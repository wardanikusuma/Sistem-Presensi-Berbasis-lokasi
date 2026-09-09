<?php

namespace App\Http\Controllers\Athlete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(Request $request): View
    {
        $athlete = $request->user()->athlete()->with('clubs')->firstOrFail();
        $clubs = $athlete->clubs()->withCount('athletes')->get();

        return view('athlete.clubs', compact('athlete', 'clubs'));
    }
}
