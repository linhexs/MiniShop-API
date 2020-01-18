<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/7
 * Time: 18:34
 */

namespace app\api\model;


class Order extends BaseModel
{
    public $autoWriteTimestamp = true;
    protected $hidden = ['delete_time'];
    // 告诉模型这个字段是json格式的数据
    protected $json = ['snap_address', 'snap_items'];
    // 设置json数据返回时以数组格式返回
    protected $jsonAssoc = true;

    /**
     * @param $params
     * @return array
     * @throws \LinCmsTp5\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取订单列表
     */
    public static function getOrdersPaginate($params){
        $field = ['order_no', ['name', 'snap_address->name']];
        $query = self::equalQuery($field, $params);
        $query[] = self::betweenTimeQuery('start', 'end', $params);
        list($start, $count) = paginate();
        $orderList = self::where($query);
        $totalNums = $orderList->count();
        $orderList = $orderList->limit($start,$count)
            ->order('create_time desc')
            ->select();
        return [
            'collection'=>$orderList,
            'total_nums'=>$totalNums
        ];
    }
    public static function getOrderStatisticsByDate($params)
    {
        $query = [];
        // 查询时间范围
        $query[] = self::betweenTimeQuery('start', 'end', $params);
        // 查询status为2到4这个范围的记录
        // 2（已支付）,3（已发货）,4（已支付但缺货）
        $query[] = ['status', 'between', '2, 4'];

        $order = self::where($query)
            // 格式化create_time字段；做聚合查询
            ->field("FROM_UNIXTIME(create_time,'%Y-%m-%d') as date,
                    count(*) as count,sum(total_price) as total_price")
            // 查询结果按date字段分组，注意这里因为在field()中给create_time字段起了别名date，所以用date
            ->group("date")
            ->select();

        return $order;
    }
}