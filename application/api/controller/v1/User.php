<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/9
 * Time: 19:57
 */

namespace app\api\controller\v1;


use app\api\model\User as UserModel;
use LinCmsTp5\admin\exception\user\UserException;
use think\facade\Request;

class User
{
    public function getUsersPaginate(){
        $params = Request::get();
        $users =UserModel::getUsersPaginate($params);
        if($users['total_nums'] == 0){
            throw new UserException([
                'code' => 404,
                'msg' => '未查询到会员相关信息',
                'error_code' => 70013
            ]);
        }
        return $users;
    }

}