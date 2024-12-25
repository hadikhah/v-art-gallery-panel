<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ImageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->file("image"))
            $this->merge(["image_name" => $this->file("image")->getClientOriginalName()]);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            "image" => [
                "required",
                File::image()
                    ->max(1024 * 1024)
                    ->dimensions(
                        Rule::dimensions()
                            ->maxWidth(2400)
                            ->maxHeight(1200)
                    )
            ],
            "image_name" => [
                "required",
                Rule::unique('images', 'title')
                    ->where('user_id', $this->user()->id),

            ],
        ];
    }

    public function messages()
    {
        return [
            'unique' => __('image already exists'),
        ];
    }
}
