<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/17
 * Time: 10:22
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class ClientModel extends Model
{
    protected $table = 'client';

//    根据id获取
    function get_client_by_id($client_id)//客户id
    {
        $result = Db::table($this->table)
            ->where('client_id', '=', $client_id)
            ->find();
        return $result;
    }
//    获取所有的客户
     function get_all_client(){
        $result = Db::table($this->table)
            ->field('client_id,name')
            ->select();
         return $result;
     }
//     创建用户
     function add($client){
        $result = Db::table($this->table)
            ->insert($client);
        return $result;
     }
}