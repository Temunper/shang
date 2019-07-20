<?php

/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\customer\model;

use app\customer\common\Convert;
use think\Db;
use think\Model;

class Message extends Model
{
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
        $re = self::all(function ($query) use ($data) {
            $query->alias('m')
                ->field('m.message_id,m.client,m.time,ad.name,m.ip,m.content,m.phone,m.status')
                ->join('project ad', 'm.project_id = ad.project_id', 'LEFT')
                ->where('m.status', 'in', [1, 2])
                ->where('m.project_id', 'in', $data)
                ->order('m.project_id', 'asc')
                ->limit(15);
        });

        $res = [];
        if (!empty($re)) {
            foreach ($re as $k => $v) {
                $res [$k] = $v->toArray();
            }
            return $res;
        }
        return [];
    }

    //精确搜索
    public function check_exact($time1, $time2, $data, $project)
    {
        $re = self::all(function ($query) use ($time1, $time2, $data, $project) {
            $query->alias('m')
                ->field('m.message_id,m.client,m.time,ad.name,m.ip,m.content,m.phone,m.status')
                ->join('project ad', 'm.project_id = ad.project_id', 'LEFT')
                ->where('m.status', 'in', [1, 2])
                ->where('m.time', '>=', $time1)
                ->where('m.time', '<=', $time2)
                ->where($data)
                ->where('m.project_id', 'in', $project)
                ->order('m.project_id', 'asc')
                ->limit(15);
        });
        //   return dump($this->getLastSql());

        if (!empty($re)) {
            foreach ($re as $k => $v) {
                $res [$k] = $v->toArray();
            }
            return $res;
        }
        return [];

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
  /*  public function getIpAttr($ip)
    {
        return Convert::convert_ip($ip);
    }*/

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
            $query->alias('m')
                ->field('m.message_id,m.client,m.time,ad.name,m.ip,m.content,m.phone')
                ->join('ad_position ad', 'm.project_id = ad.project_id', 'LEFT')
                ->where('m.message_id', 'in', $data);
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

    public function profile()
    {
        return $this->hasOne('ad_position')->field('name');
    }


    //通过ip获取地区
    function get_city($ip)
    {
        if (empty($ip)) {
            return null;
        }
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        $ip_info = json_decode(file_get_contents($url));
        if ($ip_info->code == '1') {
            return false;
        }
        $city = $ip_info->data->region . $ip_info->data->city;
        return $city;
    }

    function get_position($ip)
    {
        if (empty($ip)) {
            return '缺少用户ip';
        }
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
        $ipContent = file_get_contents($url);
        $ipContent = json_decode($ipContent, true);
        return $ipContent;
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