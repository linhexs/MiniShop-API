<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/9/23
 * Time: 22:47
 */

namespace app\api\model;


use think\Model;
use think\model\concern\SoftDelete;

class Category extends Model
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'create_time', 'update_time','topic_img_id'];

    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id');
    }
}