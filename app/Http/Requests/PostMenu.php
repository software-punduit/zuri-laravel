<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostMenu extends FormRequest
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
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'restaurant_id' => 'required|exists:restaurants,id'
        ];
    }
}
