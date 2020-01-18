<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/9
 * Time: 19:24
 */

namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];
    public static function getUsersPaginate($params){
        $filed = ['nickname'];
        $query = self::equalQuery($filed,$params);
        list($start,$count)=paginate();
        $userList = self::where($query);
        $totalNums =  $userList->count();
        $userList = $userList->limit($start, $count)
            ->order('create_time desc')
            ->select();
        $result = [
            'collection' => $userList,
            'total_nums' => $totalNums
        ];
        return $result;
    }
}