<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/23
 * Time: 16:04
 */

namespace app\lib\exception\analysis;


use LinCmsTp5\exception\BaseException;

class AnalysisException extends BaseException
{

    public $code = 400;
    public $msg = '统计信息不存在';
    public $error_code = '70000';
}