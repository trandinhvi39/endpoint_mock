<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\AbstractRequest;

class RegisterRequest extends AbstractRequest
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
            'username' => 'required|min:3|max:50|unique:users',
            'fullname' => 'min:2|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|alpha_dash|min:6|max:128',
        ];
    }
}
