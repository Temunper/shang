<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/10
 * Time: 18:01
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AdminModel extends Model
{
    protected $table = 'admin';

//    查询密钥

//用户信息
    function get_admin($user)
    {
     $result = Db::table($this->table)->where(['user'=>$user])->find();
     return $result;
    }
}