<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PutRestaurantTable extends FormRequest
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
        $restaurantTable = $this->route('restaurant_table');
        return [
            'active' => 'sometimes|boolean',
            'name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('restaurant_tables', 'name')->ignore($restaurantTable->id)
            ],
            'reservation_fee' => 'sometimes|numeric|min:0',
            'restaurant_id' => 'sometimes|exists:restaurants,id',
            'photo' => 'sometimes|mimes:png,jpg,jpeg|max:1024'
        ];
    }
}
