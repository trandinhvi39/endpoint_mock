<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\AbstractRequest;

class StoreRequest extends AbstractRequest
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
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,gif,bmp,png|max:10240',
            'user_id' => 'required|numeric|exists:users,id',
        ];
    }
}
