<?php

namespace App\Http\Requests\Base\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
            'name' => 'filled|unique:roles,name',
            'guard_name' => 'filled|in:admin,api',
            'filed' => 'filled|array',
            'filled.*' => 'required_with:filed|exists:permissions,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'guard_name' => '分组名称',
            'filed' => '所选权限',
            'filed.*' => '所选权限的元素',
        ];
    }
}
