<?php

namespace App\Http\Requests\Index;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:20|confirmed',
            'name' => 'required',
            'identity_card' => 'required|regex:/^[1-9]{1}[0-9]{6,7}$/|unique:users',
            'bank_id' => 'required',
            'number_account' => 'required|regex:/^[0-9]{20}$/',
        ];
    }
}
