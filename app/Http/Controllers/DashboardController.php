<?php

namespace App\Http\Controllers;

use App\Models\ExhibitionView;
use App\Services\ChartService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with user details and exhibition view charts.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Inertia\Response
     */
    public function index(Request $request, ChartService $chart)
    {
        // Load user details including image count, exhibition count, and total exhibition views
        $userDetails = $request->user()->loadCount(["images", "exhibitions"])
            ->loadSum("exhibitionViewRate", "views");

        // Determine the period for the chart (day, week, or month)
        $period = $request->input("period", "day");

        // Fetch exhibition views data based on the selected period
        if ($period === "day") {
            $exhibitionViewsChart = $chart->getLast24HoursExhibitionViews($request);
        } elseif ($period === "week") {
            $exhibitionViewsChart = $chart->getLastWeekExhibitionViews($request);
        } elseif ($period === "month") {
            $exhibitionViewsChart = $chart->getLastMonthExhibitionViews($request);
        } else {
            $exhibitionViewsChart = collect();
        }

        // Render the Dashboard view with user details and chart data
        return Inertia::render(
            'Dashboard',
            compact("userDetails", "exhibitionViewsChart", "period")
        );
    }
}
