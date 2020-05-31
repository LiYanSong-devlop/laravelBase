<?php


namespace App\Service;



class CommonService
{
    public function uploadCos($file)
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

    public function uploadLocal($file)
    {
        $path = $file->store(date('Ym').'/'.date('d'),'admin');
        return url('uploads/'.$path);
    }
}
