<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    /**
     * Display a listing of the user's images.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $images = Inertia::defer(
            fn() => $request->user()->images()->orderByDesc("id")->paginate(24)
        );

        return Inertia::render('Dashboard/Images/List', compact('images'));
    }

    /**
     * Handle the upload of a new image.
     *
     * @param ImageUploadRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(ImageUploadRequest $request)
    {
        $image = $request->file('image');

        if ($image->isValid()) {
            $title = $image->getClientOriginalName();
            $filePath = "v_art_gallery/" . now()->timestamp . "_" . random_int(1000, 10000) . "." . $image->extension();

            $file = Storage::disk('liara')->put($filePath, file_get_contents($image));

            if ($file) {
                $request->user()->images()->create([
                    "title" => $title,
                    "path" => $filePath,
                    "url" => Storage::disk("liara")->url($filePath)
                ]);
            }
        }

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    /**
     * Delete a specified image.
     *
     * @param Request $request
     * @param Image $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($lang, Request $request, Image $image)
    {
        abort_if(boolean: $request->user()->id !== $image->user_id, code: 403);

        // Delete the image from storage
        $delete = Storage::disk('liara')->delete($image->path);

        if ($delete)
            // Delete the image record from the database
            $image->delete();


        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Download a specified image.
     *
     * @param Request $request
     * @param Image $image
     * @return StreamedResponse
     */
    public function download($lang, Request $request, Image $image): StreamedResponse
    {
        abort_if(boolean: $request->user()->id !== $image->user_id, code: 403);

        return Storage::disk('liara')->download($image->path, $image->title);
    }
}
