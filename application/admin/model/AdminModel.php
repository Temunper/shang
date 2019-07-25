<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/10
 * Time: 18:01
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $resultSetType = 'collection';
    protected $insert = ['status'];

//    查询密钥

//获取密钥
    function get_admin($user)
    {
        $result = Db::table($this->table)->field('verify')->where(['user' => $user])->find();
        return $result;
    }
//登陆验证
    function login($user,$pass){
        $result = Db::table($this->table)->field('id,name,user,status')->where(['user'=>$user,'pass'=>$pass])->find();
        return $result;
    }

//          读取器和修改器
    public function getStatusAttr($value)
    {
        $status = [1 => '正常', 2 => '删除'];
        return $status[$value];
    }

    public function setStatusAttr($value, $data)
    {
        return '正常' == $data['status'] ? 1 : 2;
    }
}