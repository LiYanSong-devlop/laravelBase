<?php

namespace App\Http\Requests\Base\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'user_name' => 'required|min:6|max:15|unique:admin_users,user_name',
            'nickname' => 'nullable|max:15',
            'password' => 'required|min:6|max:15',
            'repeat_password' => 'required|same:password', //same 验证字段必须与给定字段的值相同
            'mobile' => 'nullable',
            'avatar' => 'nullable',
            'position' => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'user_name' => '用户名',
            'nickname' => '昵称',
            'password' => '密码',
            'repeat_password' => '确认密码', //same 验证字段必须与给定字段的值相同
            'mobile' => '联系方式',
            'position' => '职位',
        ];
    }
}
