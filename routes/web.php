<?php

use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::prefix('{lang?}')->group(function ($route) {

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/dashboard/exhibition/list', [ExhibitionController::class, "index"])->name("exhibitions");

        Route::get('dashboard/exhibition/{exhibition}/edit', [ExhibitionController::class, "edit"])->name("exhibition.edit");

        Route::patch('dashboard/exhibition/{exhibition}/update', [ExhibitionController::class, 'update'])
            ->name("exhibition.update");

        Route::patch('dashboard/exhibition/{exhibition}/images/toggle', [ExhibitionController::class, 'toggleImages'])
            ->name("exhibition.images.toggle");

        Route::get('/dashboard/images', function () {
            return "images";
        })->name("images");
    });

    $route->get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin'       => Route::has('login'),
            'canRegister'    => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion'     => PHP_VERSION,
        ]);
    });
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

Route::get("/exhibition/test", function () {
    return view("exhibition_test");
});
