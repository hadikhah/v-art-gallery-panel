<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Song;
use App\Models\Texture;
use App\ExhibitionDefaultSettings;
use App\Http\Requests\ExhibitionSettingsUpdateRequest;
use App\TextureDefaultType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExhibitionSettingsController extends Controller
{
    /**
     * Show settings page of a specific exhibition by ID.
     *
     * @param Request $request The incoming request.
     * @param string $lang The language.
     * @param Exhibition $exhibition The exhibition.
     * @return \Inertia\Response
     */
    public function showSettings($lang, Request $request, Exhibition $exhibition)
    {
        $this->authorizeAccess($request->user(), $exhibition);

        $exhibition->load(["ceilingTexture", "wallTexture", "floorTexture", "songs"]);

        $textures = Texture::get(["id", "title", "url", "is_default", "default_type"]);
        $songs = Song::orderByDesc("id")->get(["id", "title", "url", "thumbnail_url"]);
        $textureDefaultTypes = TextureDefaultType::all();
        $defaultTextures = $textures->where("is_default", 1)->pluck("url", "default_type");
        $defaultSettings = ExhibitionDefaultSettings::getDefaultSettings();

        return Inertia::render("Dashboard/Exhibitions/Settings", [
            "exhibition" => $exhibition,
            "textureDefaultTypes" => $textureDefaultTypes,
            "defaultSettings" => $defaultSettings,
            "defaultTextures" => $defaultTextures,
            "textures" => $textures,
            "songs" => $songs,
        ]);
    }

    /**
     * Update exhibition settings.
     *
     * @param string $lang The language.
     * @param ExhibitionSettingsUpdateRequest $request The validated request.
     * @param Exhibition $exhibition The exhibition.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings($lang, ExhibitionSettingsUpdateRequest $request, Exhibition $exhibition)
    {
        $this->authorizeAccess($request->user(), $exhibition);

        $data = $request->validated();

        DB::transaction(function () use ($exhibition, $data) {
            $this->syncPlaylist($exhibition, $data);
            $exhibition->update(collect($data)->except('playlist')->toArray());
        });

        return redirect()->route('exhibitions', ["lang" => $lang])
            ->with('success', 'Settings saved successfully');
    }

    /**
     * Authorize access to the exhibition.
     *
     * @param \App\Models\User $user The authenticated user.
     * @param Exhibition $exhibition The exhibition.
     * @return void
     */
    protected function authorizeAccess($user, Exhibition $exhibition)
    {
        abort_if($user->id !== $exhibition->user_id, 403);
    }

    /**
     * Sync the playlist for the exhibition.
     *
     * @param Exhibition $exhibition The exhibition.
     * @param array $data The validated data.
     * @return void
     */
    protected function syncPlaylist(Exhibition $exhibition, array $data)
    {
        if ($this->shouldSyncPlaylist($data, $exhibition)) {
            $exhibition->songs()->sync($data['playlist']);
        } elseif (!$data['has_background_song']) {
            $exhibition->songs()->sync([]);
        }
    }

    /**
     * Determine if the playlist should be synced.
     *
     * @param array $data The validated data.
     * @param Exhibition $exhibition The exhibition.
     * @return bool
     */
    protected function shouldSyncPlaylist(array $data, Exhibition $exhibition)
    {
        return ($data['has_background_song'] && $data['has_background_song'] !== $exhibition->has_background_song) ||
            (isset($data['playlist']) && $data['has_background_song']);
    }
}
