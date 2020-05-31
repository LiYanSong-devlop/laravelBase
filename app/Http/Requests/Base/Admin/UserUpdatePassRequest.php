<?php

namespace App\Http\Requests\Base\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePassRequest extends FormRequest
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
            'password' => 'required|min:6|max:15',
            'repeat_password' => 'required|same:password', //same 验证字段必须与给定字段的值相同
        ];
    }
}
