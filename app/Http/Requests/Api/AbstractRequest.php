<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class AbstractRequest extends FormRequest
{
    protected function formatErrors(Validator $validator)
    {
        return [
            'message' => [
                'status' => false,
                'code' => 422,
                'description' => $validator->errors()->all(),
            ]
        ];
    }
}
