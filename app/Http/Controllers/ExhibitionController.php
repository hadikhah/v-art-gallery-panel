<?php

namespace App\Http\Controllers;

use App\Actions\ExhibitionViewAction;
use App\ExhibitionDefaultSettings;
use App\Http\Requests\ExhibitionImageAttachRequest;
use App\Http\Requests\ExhibitionStoreRequest;
use App\Http\Requests\ExhibitionUpdateRequest;
use App\Models\Exhibition;
use App\Models\Texture;
use App\TextureDefaultType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Class ExhibitionController
 *
 * Handles the management of exhibitions, including CRUD operations.
 */
class ExhibitionController extends Controller
{
    /**
     * Display a listing of the exhibitions.
     *
     * @param Request $request The incoming request.
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $exhibitions = Inertia::defer(
            fn() =>
            $request->user()->exhibitions()->orderBy("id", "DESC")->paginate(10)
        );

        $statusList = Inertia::defer(fn() => Exhibition::getStatusList());

        return Inertia::render("Dashboard/Exhibitions/List", compact("exhibitions", "statusList"));
    }

    /**
     * Show the form for creating a new exhibition.
     *
     * @param string $lang The language code.
     * @return \Inertia\Response
     */
    public function create($lang)
    {
        $statusList = Exhibition::getStatusList();

        return Inertia::render("Dashboard/Exhibitions/Create", compact("statusList"));
    }

    /**
     * Store a newly created exhibition in storage.
     *
     * @param string $lang The language code.
     * @param ExhibitionStoreRequest $request The validated request data.
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Show the form for editing the specified exhibition.
     *
     * @param string $lang The language code.
     * @param Request $request The incoming request.
     * @param Exhibition $exhibition The exhibition instance.
     * @return \Inertia\Response
     */
    public function edit($lang, Request $request, Exhibition $exhibition)
    {
        abort_if($request->user()->id !== $exhibition->user_id, 403);

        $exhibitionImages = Inertia::defer(
            fn() =>
            $exhibition->images()->orderBy("id", "desc")
                ->paginate(12, pageName: "userImagePage")
                ->withQueryString()
        );

        $allUserImages = Inertia::defer(
            fn() =>
            $request->user()->images()->whereDoesntHave("exhibition", function ($query) use ($exhibition) {
                $query->where("imageables.imageable_id", $exhibition->id);
            })
                ->orderBy("id", "desc")
                ->paginate(12, pageName: "exhibitionImagePage")
                ->withQueryString()
        );

        $statusList = Exhibition::getStatusList();

        return Inertia::render("Dashboard/Exhibitions/Edit", compact("exhibition", "exhibitionImages", "allUserImages", "statusList"));
    }

    /**
     * Update the specified exhibition in storage.
     *
     * @param string $lang The language code.
     * @param ExhibitionUpdateRequest $request The validated request data.
     * @param Exhibition $exhibition The exhibition instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($lang, ExhibitionUpdateRequest $request, Exhibition $exhibition)
    {
        abort_if($request->user()->id !== $exhibition->user_id, 403);

        $data = $request->validated();

        $exhibition->update($data);

        return redirect()->to(route("exhibitions", ["lang" => $lang]))->with("success", __("successfully updated"));
    }

    /**
     * Toggle images for the specified exhibition.
     *
     * @param string $lang The language code.
     * @param ExhibitionImageAttachRequest $request The validated request data.
     * @param Exhibition $exhibition The exhibition instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleImages($lang, ExhibitionImageAttachRequest $request, Exhibition $exhibition)
    {
        abort_if($request->user()->id !== $exhibition->user_id, 403);

        $data = $request->validated();

        $exhibition->images()->toggle($data["selectedImages"]);

        return redirect()->back()->with("success", __("successfully added"));
    }

    /**
     * Remove the specified exhibition from storage.
     *
     * @param string $lang The language code.
     * @param Request $request The incoming request.
     * @param Exhibition $exhibition The exhibition instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($lang, Request $request, Exhibition $exhibition)
    {
        abort_if($request->user()->id !== $exhibition->user_id, 403);

        DB::transaction(function () use ($exhibition) {
            $exhibition->images()->sync([]);
            $exhibition->delete();
        });

        return redirect()->back()->with("success", __("successfully deleted"));
    }


    /**
     * Visit a specific exhibition by slug.
     *
     * @param Request $request The incoming request.
     * @param string $slug The exhibition slug.
     * @return \Illuminate\View\View
     */
    public function visit(Request $request, $slug, ExhibitionViewAction $action)
    {
        $exhibition = Exhibition::where("slug", $slug)
            ->with(["songs", "ceilingTexture", "wallTexture", "floorTexture"])
            ->first();

        abort_if(empty($exhibition), 404);
        abort_if($exhibition->status == Exhibition::STATUS_PRIVATE && Auth::id() !== $exhibition->user_id, 403);

        $defaultTextures = Texture::query()->where("is_default", 1)->pluck("url", "default_type");

        $action->handle($exhibition);

        $mapSize = $exhibition->map_size ?? ExhibitionDefaultSettings::DEFAULT_MAP_SIZE();

        $wallThickness = $exhibition->wall_thickness ??
            ExhibitionDefaultSettings::DEFAULT_WALL_THICKNESS();

        $cellSize = $exhibition->cell_size ?? ExhibitionDefaultSettings::DEFAULT_CELL_SIZE();

        // $exhibition->increment("view_count");

        return view("exhibition_visit", compact("exhibition", "mapSize", "wallThickness", "cellSize", "defaultTextures"));
    }

    /**
     * Get images for the specified exhibition.
     *
     * @param Request $request The incoming request.
     * @param Exhibition $exhibition The exhibition instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function images(Request $request, Exhibition $exhibition)
    {
        abort_if($exhibition->status == Exhibition::STATUS_PRIVATE && Auth::id() !== $exhibition->user_id, 403);

        $data = $exhibition->images()
            ->select(["path as file", "title"])
            ->get()
            ->makeHidden("pivot")
            ->toArray();

        return response()->json($data);
    }
}
