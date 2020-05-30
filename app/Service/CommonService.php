<?php


namespace App\Service;


use App\Models\Base\Image;

class CommonService
{
    public $imagesModel;

    public function __construct(Image $image)
    {
        $this->imagesModel = $image;
    }

    public function upload($file)
    {
        $dir_name = date("Ym", time()) . '/' . date("d");
        $file_content = \Storage::disk('cosv5')->put('images' . '/' . $dir_name, $file);
        $file_url = \Storage::disk('cosv5')->url($file_content);
        //去掉多余的参数  因为返回的url是携带参数的
        $file_url = explode('?', $file_url);
        return [
            'file_url' => $file_url[0]
        ];
    }
}
