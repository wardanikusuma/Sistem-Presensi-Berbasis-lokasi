<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->isAdmin()) {
            return view('admin.dashboard');
        }

        if ($request->user()->isAthlete()) {
            return view('athlete.dashboard');
        }

        return view('dashboard');
    }
}
