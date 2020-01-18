<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/9/23
 * Time: 22:58
 */

namespace app\api\validate\category;


use LinCmsTp5\validate\BaseValidate;

class CategoryForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chsDash',
        'description' => 'require|chsDash',
        'topic_img_id' => 'require|number',
    ];
    /**
     * 声明一个名叫edit的场景
     */
    public function sceneEdit()
    {
        return $this->append('id', 'require|number')
            ->remove('description', 'require');
    }
}