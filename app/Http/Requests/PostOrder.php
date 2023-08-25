<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostOrder extends FormRequest
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
            'product_quantities.*' => [
                'required',
                'numeric',
                'min:0'
            ],

            'product_ids.*' => [
                'required',
                'exists:menu,id'
            ]
        ];
    }
}
