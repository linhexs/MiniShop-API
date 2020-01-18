<?php


namespace app\api\model;


use think\Model;
use think\model\concern\SoftDelete;

class BannerItem extends Model
{
    use SoftDelete;
    protected $hidden = ['delete_time','update_time'];
    public function img(){
        return $this->belongsTo('Image','img_id');
    }
}