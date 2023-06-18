<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutRestaurantStaff extends FormRequest
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
            'name' => 'sometimes|string|max:100|nullable',
            'password' => 'sometimes|string|min:8|confirmed|nullable',
            'restaurant_id' => 'sometimes|numeric|exists:restaurants,id|nullable'
        ];
    }
}
