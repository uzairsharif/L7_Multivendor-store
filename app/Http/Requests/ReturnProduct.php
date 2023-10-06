<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnProduct extends FormRequest
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
            'product_id' => 'required|string|max:3',
            'product_quantity' => 'required|integer|min:1',
            'product_return_rate' => 'required|integer|min:1',
            'product_return_total' => 'required|integer',
        ];
    }
}
