<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PutRestaurant extends FormRequest
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
        $restaurant = $this->route('restaurant');
        return [
            'active' => 'sometimes|boolean',
            'name' => [
                'string',
                'sometimes',
                'max:100',
                Rule::unique('restaurants', 'name')->ignore($restaurant->id)
            ],
            'phone' => 'string|sometimes|max:20',
            'address' => 'string|sometimes|max:500',
            'email' => 'email|sometimes|max:100',
            'description' => 'string|sometimes|max:500',
        ];
    }
}
