<?php

namespace App\Http\Requests\Base\RolePermission;

use Illuminate\Foundation\Http\FormRequest;

class SetAdminUserRoleRequest extends FormRequest
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
            'user_id' => 'required|exists:admin_users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'required|integer|exists:roles,id'
        ];
    }

    public function attributes()
    {
        return [
            'roles_ids' => '角色',
            'role_ids.*' => '角色中ID',
        ];
    }
}
