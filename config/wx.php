<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/14
 * Time: 18:11
 */


return [
    'app_id' => '', #绑定支付的APPID（必须配置，开户邮件中可查看）
    'merchant_id' => '', #商户号（必须配置，开户邮件中可查看）
    'sign_type' => 'MD5', #签名加密类型，直接MD5即可
    'key' => '', # 微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
];