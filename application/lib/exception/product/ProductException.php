<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/6
 * Time: 19:00
 */

namespace app\lib\exception\product;


use LinCmsTp5\exception\BaseException;

class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '商品不存在';
    public $errorCode = 70002;
}