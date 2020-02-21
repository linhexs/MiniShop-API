<?php

namespace app\api\controller\cms;

use app\lib\file\ZergImageUploader;
use think\facade\Request;
use app\lib\file\LocalUploader;
use app\lib\exception\file\FileException;

/**
 * Class File
 * @package app\api\controller\cms
 */
class File
{
    /**
     * @return mixed
     * @throws FileException
     * @throws \LinCmsTp\exception\FileException
     */
    public function postFile()
    {
        try {
            $request = Request::file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $file = (new LocalUploader($request))->upload();
        return $file;
    }

    /**
     * @return mixed
     * @throws FileException
     * 自定义上传图片
     */
    public function postCustomImage()
    {
        try {
            $request = Request::file();
        } catch (\Exception $e) {
            throw new FileException([
                'msg' => '字段中含有非法字符',
            ]);
        }
        $result = (new ZergImageUploader($request))->upload();

        return $result;
    }
}
