<?php

namespace App\Http\Requests;

use App\Models\Exhibition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionStoreRequest extends FormRequest
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
        return [
            "title" => ["required", "string", "max:255"],
            "slug" => [
                "required",
                "string",
                Rule::unique("exhibitions")
            ],
            "description" => ["nullable", "string", "max:500"],
            "status" => ["required", Rule::in(Exhibition::$statusList)],
        ];
    }
}
