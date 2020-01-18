<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/14
 * Time: 18:04
 */

namespace app\api\service;
use app\lib\exception\pay\PayException;

require_once "../extend/wx_pay/WxPay.Api.php";


class WxPay
{
    private $orderNo;
    private $config;
    public function __construct($orderNo)
    {
        $this->orderNo = $orderNo;
        $this->config = new WxPayConfig('wx');
    }
    public function getWxOrderStatus(){
        $inputObj = $this->generateOrderQuery();
        try {
            $payStatus = \WxPayApi::orderQuery($this->config, $inputObj);
            if ($payStatus['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $payStatus['err_code_des']]);
            }
            $result = [
                'trade_state' => $payStatus['trade_state'],
                'trade_state_desc' => $payStatus['trade_state_desc'],
                'out_trade_no' => $payStatus['out_trade_no'],
                'transaction_id' => $payStatus['transaction_id'],
                'is_subscribe' => $payStatus['is_subscribe'],
                'total_fee' => $payStatus['total_fee'],
                'cash_fee' => $payStatus['cash_fee'],
                'time_end' => $payStatus['time_end'],
                'attach' => $payStatus['attach'],
            ];
            return $result;
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }

    }
    protected function generateOrderQuery()
    {
        // 实例化订单查询输入对象
        $inputObj = new \WxPayOrderQuery();
        // 设置商户订单号，用于查询条件
        $inputObj->SetOut_trade_no($this->orderNo);
        return $inputObj;
    }
    public function config($name)
    {
        $this->config = new WxPayConfig($name);
        return $this;
    }

}