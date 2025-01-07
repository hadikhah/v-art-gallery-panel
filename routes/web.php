<?php

use App\ExhibitionDefaultSettings;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\ExhibitionSettingsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::prefix('{lang?}')->group(function ($route) {

    Route::middleware(['auth', 'verified'])->group(function () {

        // ------------------ profile ------------
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        // ------------------ main dashboard page ------------

        Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard');

        // ------------------ profile ------------
        Route::get('/dashboard/exhibition/list', [ExhibitionController::class, "index"])->name("exhibitions");

        Route::get('dashboard/exhibition/{exhibition}/edit', [ExhibitionController::class, "edit"])->name("exhibition.edit");

        Route::get('dashboard/exhibition/create', [ExhibitionController::class, "create"])->name("exhibition.create");

        Route::get('dashboard/exhibition/{exhibition}/settings', [ExhibitionSettingsController::class, "showSettings"])->name("exhibition.settings");

        Route::patch('dashboard/exhibition/{exhibition}/settings', [ExhibitionSettingsController::class, "updateSettings"])->name("exhibition.settings.update");

        Route::post('dashboard/exhibition/store', [ExhibitionController::class, "store"])->name("exhibition.store");

        Route::patch('dashboard/exhibition/{exhibition}/update', [ExhibitionController::class, 'update'])
            ->name("exhibition.update");

        Route::patch('dashboard/exhibition/{exhibition}/images/toggle', [ExhibitionController::class, 'toggleImages'])
            ->name("exhibition.images.toggle");

        Route::delete("dashboard/exhibition/{exhibition}", [ExhibitionController::class, "destroy"])->name("exhibition.destroy");


        //-------------------- gallery

        Route::get('/dashboard/images/list', [ImageController::class, "index"])->name("images");

        Route::post("/dashboard/images/upload", [ImageController::class, "upload"])->name("images.upload");
        Route::delete("/dashboard/image/{image}/delete", [ImageController::class, "destroy"])->name("images.destroy");

        Route::get("/dashboard/image/{image}/download", [ImageController::class, "download"])->name("images.download");
    });

    $route->get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin'       => Route::has('login'),
            'canRegister'    => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion'     => PHP_VERSION,
        ]);
    })->name("home");
})->middleware(\App\Http\Middleware\Locale::class)
    ->where(["lang" => "fa|en"]);;

Route::get("/exhibition/v30", function () {
    return view("exhibition1");
});

Route::get("/exhibition/v60", function () {
    return view("exhibition2");
});

Route::get("/exhibition/v100", function () {
    return view("exhibition3");
});

Route::get("/exhibition/visit/{slug}", [ExhibitionController::class, "visit"])->name("exhibition.visit");

Route::get("/exhibition/map", function () {
    return response()->json(ExhibitionDefaultSettings::getDefaultSettings());
});

Route::get("/exhibition/{exhibition}/images", [ExhibitionController::class, "images"])
    ->name("exhibition.images");
