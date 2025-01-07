<?php

namespace App\Services;

use App\Models\ExhibitionView;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class ChartService
{

    /**
     * Get exhibition views for the last 24 hours, starting from the same hour yesterday until now.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Support\Collection
     */
    public function getLast24HoursExhibitionViews(Request $request)
    {
        $endTime = now();
        $startTime = now()->subHours(24);

        // Fetch views data for the last 24 hours
        $views = ExhibitionView::query()
            ->where("user_id", $request->user()->id)
            ->whereBetween("view_date", [$startTime->toDateString(), $endTime->toDateString()])
            ->orderBy('view_date')
            ->orderBy('view_hour')
            ->get(['view_date', 'view_hour', 'views']);

        // Create a range of hours for the last 24 hours
        $hoursRange = collect();
        for ($i = 1; $i <= 24; $i++) {
            $hour = $startTime->copy()->addHours($i)->format("m-d H:00");
            $hoursRange->put($hour, 0); // Initialize all hours with 0 views
        }

        // Update the view counts for hours that have data
        $views->each(function ($view) use ($hoursRange) {
            $currentKey = Carbon::parse($view->view_date)->setHour($view->view_hour)->format("m-d H:00");

            $hoursRange->put($currentKey, $view->views + $hoursRange[$currentKey]);
        });

        return $hoursRange;
    }

    /**
     * Get exhibition views for the last week.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Support\Collection
     */
    public function getLastWeekExhibitionViews(Request $request)
    {
        $endTime = now();
        $startTime = now()->subDays(7);

        // Fetch views data for the last 7 days
        $views = ExhibitionView::query()
            ->where("user_id", $request->user()->id)
            ->whereBetween("view_date", [$startTime->toDateString(), $endTime->toDateString()])
            ->orderBy('view_date')
            ->get(['view_date', 'views']);

        // Create a range of dates for the last 7 days
        $daysRange = collect();
        for ($i = 1; $i <= 7; $i++) {
            $day = $startTime->copy()->addDays($i)->format("Y-m-d");
            $daysRange->put($day, 0); // Initialize all days with 0 views
        }


        // Update the view counts for dates that have data
        $views->each(function ($view) use ($daysRange) {

            $currentKey = Carbon::parse($view->view_date)->format("Y-m-d");

            $daysRange->put($currentKey, $view->views + $daysRange[$currentKey]);
        });


        return $daysRange;
    }


    /**
     * Get exhibition views for the last month.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Support\Collection
     */
    public function getLastMonthExhibitionViews(Request $request)
    {
        $endTime = now();
        $startTime = now()->subDays(30);

        // Fetch views data for the last 30 days
        $views = ExhibitionView::query()
            ->where("user_id", $request->user()->id)
            ->whereBetween("view_date", [$startTime->toDateString(), $endTime->toDateString()])
            ->orderBy('view_date')
            ->get(['view_date', 'views']);

        // Create a range of dates for the last 30 days
        $daysRange = collect();
        for ($i = 1; $i <= 30; $i++) {
            $day = $startTime->copy()->addDays($i)->format("y-m-d");
            $daysRange->put($day, 0); // Initialize all days with 0 views
        }

        // Update the view counts for dates that have data
        $views->each(function ($view) use ($daysRange) {

            $currentKey = Carbon::parse($view->view_date)->format("y-m-d");

            $daysRange->put($currentKey, $view->views + $daysRange[$currentKey]);
        });

        return $daysRange;
    }
}
