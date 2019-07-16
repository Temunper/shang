<?php

/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class Message extends Model
{
    //查询客户拥有的项目id
    public function check_project($client_id)
    {
        return Db::table('client_project')->field('project_id')
            ->where('client_id', $client_id)->select();
    }


    //留言状态获取器
    public function getStatusAttr($value)
    {
        //1正常2未导出3已导出4客户删除5系统删除
        $status = [1 => '正常', 2 => '未导出', 3 => '已导出', 4 => '客户删除', 5 => '系统删除'];
        return $status[$value];
    }

    //留言时间修改器
    public function getTimeAttr($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    //软删除客户的留言,将留言状态修改为4
    public function delete_message($data)
    {
        return dump(Db::table('message')
            ->where('message_id', $data)
            ->update(['status' => 4]));
    }

    public function show_client_message($data, $limit)
    {
        //显示留言数据
        $re = self::all(function ($query) use ($data, $limit) {
            $query->alias('m')
                ->field('m.message_id,m.client,m.time,ad.name,m.ip,m.content,m.phone,m.status')
                ->join('project ad', 'm.project_id = ad.project_id', 'LEFT')
                ->where('m.status', 'in', [1, 2, 3])
                ->where('m.project_id', $data)
                ->limit($limit)->order('m.project_id', 'asc');
        });
        foreach ($re as $k => $v) {
            $res [$k] = $v->toArray();
        }
        return $res;


    }

    //查询要下载的留言
    public function to_download($data)
    {
        $re = self::all(function ($query) use ($data) {
            $query->alias('m')
                ->field('m.message_id,m.client,m.time,ad.name,m.ip,m.content,m.phone,m.status')
                ->join('ad_position ad', 'm.project_id = ad.project_id', 'LEFT')
                ->where('message_id', $data);
        });
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


}