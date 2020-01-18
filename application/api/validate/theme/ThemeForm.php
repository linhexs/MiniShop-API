<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/9/23
 * Time: 21:02
 */

namespace app\api\validate;


use LinCmsTp5\validate\BaseValidate;

class ThemeForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chsDash',
        'description' => 'require|chsDash',
        'topic_img_id' => 'require|number',
        'head_img_id' => 'require|number'
    ];
    public function sceneEdit()
    {
        return $this->remove('name', 'require')
            ->remove('description', 'require')
            ->remove('topic_img_id', 'require')
            ->remove('head_img_id', 'require');
    }
}