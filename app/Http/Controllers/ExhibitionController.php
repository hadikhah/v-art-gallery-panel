<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionImageAttachRequest;
use App\Http\Requests\ExhibitionStoreRequest;
use App\Http\Requests\ExhibitionUpdateRequest;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExhibitionController extends Controller
{

    public function index(Request $request)
    {
        $exhibitions =
            Inertia::defer(
                fn() =>
                $request->user()->exhibitions()->orderBy("id", "DESC")->paginate(10)
            );

        $statusList = Exhibition::getStatusList();

        return Inertia::render("Dashboard/Exhibitions/List", compact("exhibitions", "statusList"));
    }

    public function create($lang)
    {

        $statusList = Exhibition::getStatusList();

        return Inertia::render("Dashboard/Exhibitions/Create", compact("statusList"));
    }

    public function store($lang, ExhibitionStoreRequest $request)
    {

        // abort if not allowed

        $exhibition = $request->user()->exhibitions()->create($request->validated());

        return redirect()->route(
            "exhibition.edit",
            parameters: [
                "exhibition" => $exhibition->id,
                "lang" => $lang
            ]
        )
            ->with("message", __("successfully created"));
    }

    public function edit($lang, Request $request, Exhibition $exhibition)
    {
        abort_if(boolean: $request->user()->id !== $exhibition->user_id, code: 403);

        $exhibitionImages = Inertia::defer(
            fn() =>
            $exhibition->images()->orderBy("id", "desc")
                ->paginate(
                    perPage: 12,
                    pageName: "userImagePage"
                )->withQueryString()
        );

        $allUserImages = Inertia::defer(

            fn() =>
            $request->user()->images()->whereDoesntHave("exhibition", function ($query) use ($exhibition) {
                $query->where("imageables.imageable_id", $exhibition->id);
            })
                ->orderBy("id", "desc")
                ->paginate(
                    perPage: 12,
                    pageName: "exhibitionImagePage"
                )->withQueryString()
        );

        $statusList = Exhibition::getStatusList();

        return Inertia::render("Dashboard/Exhibitions/Edit", compact("exhibition", "exhibitionImages", "allUserImages", "statusList"));
    }



    public function update($lang, ExhibitionUpdateRequest $request, Exhibition $exhibition)
    {
        abort_if(boolean: $request->user()->id !== $exhibition->user_id, code: 403);

        $data = $request->validated();

        $exhibition->update($data);

        return redirect()->back()->with("success", __("successfully updated"));
    }

    public function toggleImages($lang, ExhibitionImageAttachRequest $request, Exhibition $exhibition)
    {
        abort_if(boolean: $request->user()->id !== $exhibition->user_id, code: 403);

        $data = $request->validated();

        $exhibition->images()->toggle($data["selectedImages"]);

        return redirect()->back()->with("success", __("successfully added"));
    }

    public function destroy($lang, Request $request, Exhibition $exhibition)
    {
        abort_if(boolean: $request->user()->id !== $exhibition->user_id, code: 403);

        DB::transaction(function () use ($exhibition) {

            $exhibition->images()->sync([]);

            $exhibition->delete();
        });

        return redirect()->route("exhibitions", ["lang" => $lang])->with("success", __("successfully deleted"));
    }
}
