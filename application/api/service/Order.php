<?php

namespace app\api\service;
use app\api\model\DeliverRecord;
use app\api\model\Order as OrderModel;
use app\lib\exception\order\OrderException;
use app\lib\enum\OrderStatusEnum;
use app\lib\token\Token;
use Finecho\Logistics\Logistics;
use think\Db;
use think\Exception;

class Order
{
    private $order;

    public function __construct($orderId)
    {
        $order = OrderModel::get($orderId);
        if (!$order) {
            throw new OrderException(
                ['code' => 404, 'msg' => '指定的订单不存在']
            );
        }
        $this->order = $order;
    }
    /**
     * @param $company
     * @param $number
     * @return boolean
     * @throws OrderException
     * 发货
     */
    public function deliverGoods($company, $number)
    {
        if ($this->order->status != OrderStatusEnum::PAID && $this->order->status !== OrderStatusEnum::PAID_BUT_OUT_OF) {
            Throw new OrderException(
                ['msg' => '当前订单不允许发货，请检查订单状态', 'error_code' => '70008']
            );
        }
        Db::startTrans();
        try {
            DeliverRecord::create([
                'order_no' => $this->order->order_no,
                'comp' => $company,
                'number' => $number,
                'operator' => Token::getCurrentName()
            ]);
            $this->order->status = OrderStatusEnum::DELIVERED;
            $this->order->save();
            Db::commit();
            return true;
        } catch (Exception $ex) {
            // 回滚事务
            Db::rollback();
            throw new OrderException(['msg' => '订单发货不成功', 'error_code' => '70009']);
        }
    }
    /**
     * @param $orderNo
     * @return mixed
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 发货信息
     */
    public static function queryLogistics($orderNo){
        $deliverRecord = DeliverRecord::where('order_no', $orderNo)->find();
        if (!$deliverRecord) {
            throw new OrderException(['msg' => '未查询到指定订单号发货单记录', 'error_code' => 70011]);
        }
        $cache = cache($deliverRecord->comp . $deliverRecord->number);
        if ($cache){
            return $cache;
        }
        $config = config('logistics.config');
        $comp = config('logistics.comp')[$deliverRecord->comp];
        try {
            $logisticsOrder = (new Logistics($config))->query($deliverRecord->number, $comp);
            cache($deliverRecord->comp . $deliverRecord->number, $logisticsOrder['list'], 1200);
            // 返回查询结果
            return $logisticsOrder['list'];
        } catch (\Finecho\Logistics\Exceptions\Exception $ex) {
            throw new OrderException(['msg' => $ex->getMessage(), 'error_code' => 70012]);
        }
    }
}