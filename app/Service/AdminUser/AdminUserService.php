<?php


namespace App\Service\AdminUser;


use App\Models\Base\AdminUser;

class AdminUserService
{
    protected $model;

    public function __construct(AdminUser $adminUser)
    {
        $this->model = $adminUser;
    }

    public function create($data)
    {
        return $this->model->query()->create($data);
    }
}
