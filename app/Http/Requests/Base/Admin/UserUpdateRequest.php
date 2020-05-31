<?php

namespace App\Http\Requests\Base\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'nickname' => 'filled|string|min:6|max:15',
            'avatar' => 'filled',
        ];
    }

    public function attributes()
    {
        return [
            'nickname' => '昵称',
            'avatar' => '头像',
        ];
    }
}
