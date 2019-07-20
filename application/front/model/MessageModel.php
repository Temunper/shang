<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/20
 * Time: 16:38
 */

namespace app\front\model;


class MessageModel
{
    protected $table = 'message';

    //新增客户留言
    public function new_message($param)
    {
        return Db::table($this->table)->insert($param);
    }
}