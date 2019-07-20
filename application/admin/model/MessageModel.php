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

    //查询所有留言
    public function search_message($params = null)
    {
        $re = Db::table($this->table)->alias('a')
            ->field('a.article_id,a.type,p.name,a.title,a.lis_img,a.brief,a.content,a.source,
                    a.create_time,a.update_time,a.status')
            ->join('project p', 'a.project_id = p.project_id', 'LEFT')
            ->where('a.status', 'in(1,2)')
            ->order('create_time', 'DESC')
            ->where($params)
            ->paginate(20);
        return $re;
    }

    //系统删除留言
    public function system_do_delete($params)
    {
        $status = 4;
        $time = time();
        $re = Db::table($this->table)->where('message_id', 'in', $params)
            ->update(['status' => $status, 'update' => $time]);
        return $re;
    }


    //留言状态获取器
    public function getStatusAttr($value)
    {
        //1正常2未导出3已导出4客户删除5系统删除
        $status = [1 => '未导出', 2 => '已导出', 3 => '客户删除', 4 => '系统删除'];
        return $status[$value];
    }

    //留言时间获取器
    public function getTimeAttr($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    //根据ip转换成地区
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
}