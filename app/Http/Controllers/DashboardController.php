<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $imageCount = $request->user()->images()->count();

        $exhibitionCount = $request->user()->exhibitions()->count();

        return Inertia::render('Dashboard', compact("imageCount", "exhibitionCount"));
    }
}
