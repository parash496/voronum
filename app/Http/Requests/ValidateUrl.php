<?php

namespace Url\Http\Requests;

use Url\Http\Requests\Request;

class ValidateUrl extends Request
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
            'email' => 'email',
            'pass' => 'required',
            'url' => 'required|url',
        ];
    }
}
