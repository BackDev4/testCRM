<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fio' => 'required',
            'name' => 'required|min:2',
            'price' => 'required|integer',
            'city' => 'required',
            'date' => 'required|date',
            'form' => 'required',
            'phone' => 'required|min:9',
        ];
    }
}
