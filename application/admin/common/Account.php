<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 17:07
 */

namespace app\admin\common;


use Overtrue\Pinyin\Pinyin;

class Account
{
//    创建账号
    public static function create($name)     //客户名
    {
        $pass = null;//加密后密码
        $user = null;//用户账号
        $verify = null;//密钥
        $password = null;//用户密码
        for ($i = 0; $i < 21; $i++) {//循环随机获取长度为21的字符串
            switch (rand(0, 2)) {
                case 0:
                    $pass .= chr(rand(48, 57));
                    break;
                case 1:
                    $pass .= chr(rand(64, 90));
                    break;
                case 2:
                    $pass .= chr(rand(97, 122));
                    break;
            }
        }
        $pinyin = new pinyin();//创建拼音对象
        $password = substr($pass, 0, 12);//生成用户密码
        $verify = substr($pass, 12, 6);//生成密钥
        $num = substr($pass, 18, 3);//账号随机数
        $pass = md5($password . $verify);//密码、密钥加密成数据库密码
        $user = $pinyin->convert($name, PINYIN_KEEP_ENGLISH);//将用户名转成拼音数组
        $user = implode('', $user) . $num;//将拼音转成字符串并加上随机数
        $client = ['client' => ['name' => $name, 'user' => $user, 'verify' => $verify, 'pass' => $pass], 'password' => $password];//将客户数据封装
        return $client;//返回
    }

}