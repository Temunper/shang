<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 9:03
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class Client2 extends Model
{


    //新增客户端用户
    public function add_client($data)
    {
        // return $re = Db::query('select verify from client where name=? limit  1', [$name]);
        self::data($data);
        return self::save();
    }

    //验证是否已存在的用户名
    public function check_name($user)
    {
        return self::get(['name' => $user]);

    }

    //验证登录
    public function check_login($map)
    {
        self::get($map);
        return dump($this->getLastSql());
    }

    //通过账户名查询秘钥
    public function get_verify($name)
    {
        return $re = Db::query('select verify from client where name=? limit  1', [$name]);
        //return self::get(['name' => $name])->toArray();
    }

    //通过id查询用户是否存在
    public function check_client($client_id)
    {
        return Db::query('select * from client where client_id=?', [$client_id]);
    }

    //软删除
    public function soft_delete($client_id)
    {
        return Db::query('update client set status=2 where client_id=?', [$client_id]);
    }


    //用户状态读取器
    public function getStatusAttr($value)
    {
        $status = [0 => '禁用', 1 => '正常', 2 => '已删除'];
        return $status[$value];
    }
}