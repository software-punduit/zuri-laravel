<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRestaurantStaff extends FormRequest
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
            'name' => 'string|required|max:100',
            'email' => 'email|required|max:100|unique:users,email',
            'password' => 'string|required|min:8|max:100|confirmed',
            'restaurant_id' => 'numeric|required|exists:restaurants,id'
        ];
    }
}
