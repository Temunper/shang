<?php

/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\customer\model;


use Org\Net\IpLocation;
use think\Db;
use think\Model;

class Message extends Model
{
    protected $ip;
    protected $field = true;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->ip = new IpLocation('UTFWry.dat');
    }

    //查询客户拥有的项目id
    public function check_project($client_id)
    {
        return Db::table('client_project')->where('client_id', $client_id)->column('project_id');;
    }

    public function check_project_name($data)
    {
        $result = Db::table('project')->field('project_id,name')->where('project_id', 'in', $data)->select();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }

    //显示所有留言
    public function show_client_message($data)
    {
        //显示留言数据
        return $re = self::order('time', 'desc')->where('status', 'in', '1,2')
            ->paginate(15);
    }

    //精确搜索
    public function check_exact($time1, $time2, $data)
    {

        return $re = self::where('status', 'in', [1, 2])
            ->where('time', '>=', $time1)
            ->where('time', '<=', $time2)
            ->where($data)
            ->order('time', 'desc')
            ->paginate(15);
        //    return dump($this->getLastSql());
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

//客户导出数据，将留言状态status修改为3
    public function cheange_status($data)
    {
        return self::where('message_id', 'in', $data)->update(['status' => 2]);

    }


//查询要下载的留言
    public function to_download($data)
    {
        $re = self::all(function ($query) use ($data) {
            $query
                ->field('message_id,client,time,project_id,ip,content,phone')
                ->where('message_id', 'in', $data);
        });
        //dump($this->getLastSql());exit;
        if (!empty($re)) {
            foreach ($re as $k => $v) {
                $result[$k] = $v->toArray();
            }
            return $result;
        } else {
            return [];
        }

    }




//通过项目ip 查询当天留言总数
//传入项目id数组$params
    public function check_count($params)
    {
        $time_old = strtotime(date('Ymd'));  //当天零点的时间戳
        $time_new = time(); // 当前时间的时间戳
        $re = self::all(function ($query) use ($time_new, $time_old, $params) {
            $query->where($params)
                ->where('time', '>=', $time_old)
                ->where('time', '<=', $time_new);
        });
        return dump($this->getLastSql());
    }
}