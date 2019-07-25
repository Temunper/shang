<?php

/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\admin\model;


use Org\Net\IpLocation;
use think\Db;
use think\Model;

class Message extends Model
{
    protected $ip;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->ip = new IpLocation('UTFWry.dat');  //创建IP转换地区第三方插件实例
    }

    //显示所有留言
    public function show_all_message()
    {
        //显示留言数据
        return self::order('time', 'desc')->where('status', 'in', '1,2')
            ->paginate(15);

    }

    //精确搜索
    public function search_message($time1, $time2, $data)
    {
        return self::where('status', 'in', '1,2')
            ->where('time', '>', $time1)
            ->where('time', '<', $time2)
            ->where($data)
            ->order('time', 'desc')
            ->paginate(15);
    }

    //系统删除功能
    public function system_do_delete($data)
    {
        $status = 4;
        return self::where('message_id', 'in', $data)->update(['status' => $status]);

    }

    //项目名称获取器
    public function getProjectIdAttr($project_id)
    {
        return $this->check_project_name2($project_id);
    }

    //根据项目id查询项目名称，返回项目名称
    public function check_project_name2($project_id)
    {
        return Db::table('project')->where('project_id', $project_id)->value('name');

    }

    //留言状态获取器
    public function getStatusAttr($value)
    {
        //1正常2未导出3已导出4客户删除5系统删除
        $status = [1 => '未导出', 2 => '已导出', 3 => '客户删除', 4 => '系统删除'];
        return $status[$value];
    }

    //留言时间修改器
    public function getTimeAttr($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    //获取ip修改器
    public function getIpAttr($ip)
    {
        $re = $this->ip->getlocation($ip);
        return $re['country'];
    }

    //软删除客户的留言,将留言状态修改为3
    public function delete_message_m($data)
    {
        return self::where('message_id', 'in', $data)->update(['status' => 3]);
    }





    //通过项目ip 查询当天留言总数
    //传入项目id数组$params

}