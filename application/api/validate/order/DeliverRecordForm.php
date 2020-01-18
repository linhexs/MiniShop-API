<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/9
 * Time: 18:49
 */

namespace app\api\validate\order;


use LinCmsTp5\validate\BaseValidate;

class DeliverRecordForm extends BaseValidate
{
    protected $rule = [
        'page' => 'require|number',
        'count' => 'require|number|between:1,15',
        'order_no' => 'length:16|alphaNum',
        'number' => 'alphaNum',
        'operator' => 'chsAlphaNum'
    ];
}