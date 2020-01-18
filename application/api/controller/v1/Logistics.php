<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/9
 * Time: 17:09
 */

namespace app\api\controller\v1;
use app\api\service\Order as OrderService;
use app\lib\exception\order\OrderException;
use think\Exception;


class Logistics
{
    /**
     * @param $orderNo
     * @return mixed
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 订单
     */
    public function getLogistics($orderNo)
    {
        $result = OrderService::queryLogistics($orderNo);
        return $result;
    }
}