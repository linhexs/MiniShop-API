<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/9
 * Time: 16:47
 */

namespace app\api\model;


class DeliverRecord extends BaseModel
{
    // 开启自动写入时间戳
    public $autoWriteTimestamp = true;
    // 隐藏字段显示
    protected $hidden = ['update_time'];
    public static function getDeliverRecordPaginate($params){
        $field = ['order_no', 'number', 'operator'];
        $query = self::equalQuery($field, $params);
        list($start, $count) = paginate();
        $courierOrderList = self::where($query);
        $totalNums = $courierOrderList->count();
        $orderList = $courierOrderList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        // 组装返回结果
        $result = [
            'collection' => $orderList,
            'total_nums' => $totalNums
        ];
        return $result;
    }

}