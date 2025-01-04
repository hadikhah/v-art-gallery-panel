<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionSettingsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isBackgroundSongEnabled = $this->input('has_background_song') === true;
        $isBackgroundSongChanged = $this->input('has_background_song') !== $this->exhibition->has_background_song;

        return [
            'map_size' => 'nullable|integer|between:1,4',
            'cell_size' => 'nullable|integer|between:5,20',
            'wall_thickness' => 'nullable|numeric|between:0.2,0.8',
            'ceiling_texture_id' => 'nullable|exists:textures,id',
            'wall_texture_id' => 'nullable|exists:textures,id',
            'floor_texture_id' => 'nullable|exists:textures,id',
            'has_background_song' => 'boolean',
            'playlist' => [
                'array',
                Rule::requiredIf($isBackgroundSongEnabled && $isBackgroundSongChanged),
                Rule::when(($isBackgroundSongEnabled &&  $isBackgroundSongChanged) || ($this->exists('playlist') && $isBackgroundSongEnabled), [
                    'min:1',
                    'exists:songs,id',
                ]),
            ],
        ];
    }
}
