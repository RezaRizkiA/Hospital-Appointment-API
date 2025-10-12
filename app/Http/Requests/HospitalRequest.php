<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
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
        /** 
         * @var \Illuminate\Http\Request 
         * $this
         * */
        $id = $this->route('hospitals');
        return [
            'name' => 'required|string|unique:hospitals,name' . $id,
            'photo' => $this->isMethod('post') ? 'required|image|mimes:jpeg,png,jpg|max:2048' : 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'about' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'post_code' => 'required|string',
            'phone' => 'required|string',
        ];
    }
}
