<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRestaurant extends FormRequest
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
            'name' => 'string|required|max:100|unique:restaurants,name',
            'phone' => 'string|required|max:20',
            'email' => 'email|sometimes|max:100',
            'description' => 'string|required|max:500',
            'address' => 'string|required|max:500'

        ];
    }
}
