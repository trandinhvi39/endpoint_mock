<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\AbstractRequest;

class LoginRequest extends AbstractRequest
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
            'email' => 'required|email|max:50',
            'password' => 'required|min:6|max:128',
        ];
    }
}
