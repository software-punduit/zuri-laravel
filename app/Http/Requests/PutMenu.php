<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutMenu extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:100',
            'price' => 'sometimes|numeric|min:0',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
            'photo' => 'sometimes|mimes:png,jpg,jpeg|max:1024',
            'active' => 'sometimes|boolean'
        ];
    }
}
