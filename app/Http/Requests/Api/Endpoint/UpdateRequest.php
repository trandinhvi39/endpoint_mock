<?php

namespace App\Http\Requests\Api\Endpoint;

use App\Http\Requests\Api\AbstractRequest;

class UpdateRequest extends AbstractRequest
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
            'fields_name' => 'required',
            'fields_value' => 'required|array',
        ];
    }
}
