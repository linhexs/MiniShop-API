<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/9/29
 * Time: 11:44
 */

namespace app\api\validate\banner;


use LinCmsTp5\validate\BaseValidate;
use think\Validate;

class BannerItemForm extends BaseValidate
{
    protected $rule = [
        'items' => 'array|require|min:1',
    ];

    public function sceneEdit()
    {
        return $this->append('items', 'checkEditItem');

    }

    public function sceneAdd()
    {
        return $this->append('items', 'checkAddItem');

    }

    protected function checkAddItem($value)
    {
        foreach ($value as $k => $v) {
            if (!empty($v['id'])) {
                return '新增轮播图元素不能包含id';
            }
            if (empty($v['img_id']) || empty($v['key_word']) ) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }

    protected function checkEditItem($value)
    {
        foreach ($value as $k => $v) {
            if (empty($v['id'])) {
                return '轮播图元素id不能为空';
            }
            if (empty($v['img_id']) || empty($v['key_word'])) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }

}