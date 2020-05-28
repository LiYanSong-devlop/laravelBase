<?php

namespace App\Http\Requests\Base\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
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
            'name' => 'required',
            'guard_name' => 'required',
            'method' => 'required|in:GET,POST',
            'path' => 'required',
            'order' => 'required|integer',
            'state' => 'required|integer',
            'parent_id' => 'nullable',
            'level' => 'required',
            'title' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'guard_name' => '分组名称',
            'method' => '请求方式',
            'path' => '请求路由',
            'order' => '排序',
            'state' => '状态',
            'parent_id' => '父级',
            'level' => '层级',
            'title'  => '中文标签',
        ];
    }
}
