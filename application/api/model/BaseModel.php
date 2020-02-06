<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/2/19
 * Time: 11:22
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    public function getUrlAttr($value, $data)
    {
        $finalUrl = $value;
        // 根据表的注释，1来自本地，2来自公网
        if($data['from'] == 1){// 如果来自本地，把本机的存放图片目录的域名地址跟$value拼接
            // 这里我们把本机的存放图片目录的域名地址写到了一个配置文件里。
            // 后续我们可能换了域名或者目录，又或者有其他来源渠道，以配置文件的形式这样以后只需改配置文件而不必改动代码
            // 利用TP5框架内置的助手函数config()快速指定获取配置文件下内容
            $finalUrl = config('setting.img_prefix').$value;
        }
        // 这里如果from不是来自本地，那么存储的会是一个完整的公网访问地址，无需处理
        // 返回处理后的url
        return $finalUrl;
    }
    protected static function equalQuery($field, $params)
    {
        $query = [];
        foreach ($field as $value) {
            if (is_array($value)) {
                if (array_key_exists($value[0], $params)) {
                    $query[] = [$value[1], '=', $params[$value[0]]];
                }
            } else {
                if (array_key_exists($value, $params)) {
                    $query[] = [$value, '=', $params[$value]];
                }
            }
        }
        return $query;
    }
    protected static function betweenTimeQuery($startField, $endField, $params, $dbField = 'create_time')
    {
        $query = [];
        if (array_key_exists($startField, $params) && array_key_exists($endField, $params)) {
            if (!empty($params[$startField]) && !empty($params[$endField])) {
                $query = array($dbField, 'between time', array($params[$startField], $params[$endField]));
            }
        }
        return $query;
    }
}