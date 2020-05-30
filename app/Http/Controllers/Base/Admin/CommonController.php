<?php

namespace App\Http\Controllers\Base\Admin;

use App\Http\Controllers\Controller;
use App\Service\CommonService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    //后台公共控制器（上传图片）

    public $service;

    public function __construct(CommonService $service)
    {
        $this->service = $service;
    }

    public function upload(Request $request)
    {
        $file = $request->file('image');
        if (empty($file)) {
            return $this->failed('缺少参数');
        }
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            return $this->failed('暂时仅支持jpeg和png格式的');
        }
        $result = $this->service->upload($file);
        return $this->success($result);
    }
}
