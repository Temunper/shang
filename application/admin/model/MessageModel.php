<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/18
 * Time: 11:43
 */

namespace app\admin\model;


use think\Db;

class MessageModel
{
    protected $table = 'message';

    //新增客户留言
    public function new_message($param)
    {
        return Db::table($this->table)->insert($param);
    }
}