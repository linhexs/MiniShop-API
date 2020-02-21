<?php


namespace app\api\controller\v1;


use app\api\model\Image;
use think\Db;
use think\Exception;
use think\facade\Request;

class UploadImg
{
    public function image(){
        $params = Request::post();
        print_r($params);exit;
        $params['from'] =1;
        Db::name('image')->insert($params);
        $userId = Db::name('image')->getLastInsID();
        return writeJson("201", ['imgId'=>$userId], '图片上传成功！' );
    }
}