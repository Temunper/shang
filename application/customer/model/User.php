<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 9:03
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class User extends Model
{
    /*  //客户端登录
      public function check_login($data)
      {
          return self::get($data);
      }*/

    //新增客户端用户
    public function add_client($data)
    {
        self::insert($data);
    }

    //通过账户名查询秘钥
    public function get_verify($user)
    {
        return Db::query('select verify from client where user=?', [$user]);

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