<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/7
 * Time: 18:34
 */

namespace app\api\controller\v1;


use app\api\model\DeliverRecord;
use app\api\service\WxPay;
use app\lib\exception\order\OrderException;
use think\facade\Request;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;

class Order
{
    /**
     * 分页查询所有订单记录
     * @validate('OrderForm')
     */
    public function getOrders()
    {
        $params = Request::get();
        $orders = OrderModel::getOrdersPaginate($params);
        if ($orders['total_nums'] === 0) {
            throw new OrderException([
                'code' => 404,
                'msg' => '未查询到相关订单',
                'error_code' => '70007'
            ]);
        }
        return $orders;
    }

    /**
     * 订单发货
     * @auth('订单发货','订单管理')
     * @param('id','订单id','require|number')
     * @param('comp','快递公司编码','require|alpha')
     * @param('number','getLogistics','require|alphaNum')
     * @throws OrderException
     */
    public function deliverGoods($id)
    {
        $params = Request::post();
        $result = (new OrderService($id))->deliverGoods($params['comp'], $params['number']);
        return writeJson(201, $result, '发货成功');
    }
    /**
     * 分页查询订单发货记录
     * @validate('DeliverRecordForm')
     */
    public function getOrderDeliverRecord(){
        $params = Request::get();
        $result = DeliverRecord::getDeliverRecordPaginate($params);
        if($result['total_nums'] == 0){
            throw new OrderException([
                'code'=>404,
                'msg'=>'未查询到相关的发货的记录',
                'error_code'=>'70010'
            ]);
        }
        return $result;
    }
    public function getOrderPayStatus($orderNo)
    {
        $result = (new WxPay($orderNo))->getWxOrderStatus();
        return $result;
    }
    public function getSecondOrderPayStatus($orderNo)
    {
        $result = (new WxPay($orderNo))->config('second_wx')
            ->getWxOrderStatus();
        return $result;
    }
}