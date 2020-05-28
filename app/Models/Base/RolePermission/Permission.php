<?php

namespace App\Models\Base\RolePermission;

use \Spatie\Permission\Models\Permission as model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
        'method',
        'path',
        'order',
        'state',
        'parent_id',
        'level',
        'title',
    ];
    //权限
    /**
     * 定义权限本身关联关系
     * 用来做树状列表显示
     * 通过循环调用children来调用数据库数据
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id')
            ->where('state', 1)
            ->orderByDesc('order')
            ->select('id','parent_id','name','title','guard_name','path')
            ->with('children');
    }
}
