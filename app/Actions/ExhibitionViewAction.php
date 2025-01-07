<?php

namespace App\Actions;

use App\Models\Exhibition;
use App\Models\ExhibitionView;

class ExhibitionViewAction
{


    public function handle(Exhibition $exhibition)
    {

        $today = now()->toDateString();
        $currentHour = now()->hour; // Get the current hour (0-23)

        ExhibitionView::query()->updateOrCreate(
            [
                "exhibition_id" => $exhibition->id,
                "user_id" => $exhibition->user_id,
                "view_date" => $today,
                "view_hour" => $currentHour,
            ],
            [
                'views' => \DB::raw('views + 1'), // Increment the views field
            ]
        );
    }
}
