<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'description' => 'required|max:3000',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1', 
            'image' => 'required|image',
            'offers.*.name' => 'required|max:128',
            'offers.*.quantity' => 'required|numeric|min:1', 
            'offers.*.price' => 'required|numeric|min:1',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['image'] = 'image';    
            $rules['offers.*.id'] = 'integer|exists:offers';
        }

        return $rules;
    }

    // Custom message
    public function messages() {
        return [
            
        ];
    }
}
