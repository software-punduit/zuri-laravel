<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRestaurantTable extends FormRequest
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
            'name' => 'string|required|max:100|unique:restaurant_tables,name',
            'reservation_fee' => 'numeric|min:0|required',
            'restaurant_id' => 'numeric|exists:restaurants,id|required',
            'photo' => ['required','mimes:png,jpg,jpeg','max:1024']
        ];
    }
}
