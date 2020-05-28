<?php

namespace App\Http\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required|min:6|max:15',
        ];
    }

    public function attributes()
    {
        return [
            'username' => '用户名',
            'password' => '密码'
        ];
    }
}
