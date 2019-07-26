<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/20
 * Time: 16:38
 */

namespace app\front\model;


use think\Db;

class MessageModel
{
    protected $table = 'message';

    //新增客户留言
    public function new_message($param)
    {
        return Db::table($this->table)->insert($param);
    }

    //插入留言记录
    public function insert_message_log($params)
    {
        return Db::table('message_log')->insert($params);
    }

    //查找当前项目ip下是存在当前手机号的记录
    public function check_exist_phone($project_id, $phone)
    {
     return    Db::table('message_log')->where('project_id', $project_id)
            ->where('phone', $phone)->find();

    }

    //查询当前ip在表中当天出现的次数
    public function check_message_number($ip)
    {
        $time1 = strtotime(date('Y-m-d'));//今日凌晨时间
        $time2 = time(); //现在的时间
        return Db::table('message_log')->where('ip', $ip)
            ->where('time', '>', $time1)
            ->where('time', '<', $time2)
            ->count();
    }


    public function insert_message($params)
    {
        return Db::table('message')->insert($params);
    }


}