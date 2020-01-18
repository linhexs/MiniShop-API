<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/6
 * Time: 19:41
 */

namespace app\api\model;


use think\model\concern\SoftDelete;

class ProductImage extends BaseModel
{
    use SoftDelete;

    protected $hidden = ['delete_time', 'product_id'];

    public function img()
    {
        return $this->belongsTo('Image', 'img_id');
    }

}