<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/9/23
 * Time: 22:52
 */

namespace app\lib\exception\category;


use LinCmsTp5\exception\BaseException;

class CategoryException extends BaseException
{
    public $code = 400;
    public $msg  = '商品分类接口异常';
    public $error_code = '70000';
}