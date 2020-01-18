<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/23
 * Time: 16:02
 */

namespace app\api\controller\v1;


use think\facade\Request;
use app\api\model\Order;
class Analysis
{
    public function getOrderBaseStatistics()
    {
        $params = Request::get();
        $result = Order::getOrderStatisticsByDate($params);
        if ($result->isEmpty()) {
            throw new AnalysisException();
        }
        return $result;
    }

}