<?php

namespace PortedCheese\BaseSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImagePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'bail|required|image',
            'name' => ["bail", "string", "max:100"],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => "Файл не найден",
            'image.image' => "Файл должен быть изображением",
        ];
    }
}
