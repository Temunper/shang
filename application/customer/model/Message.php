<?php

/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class Message extends Model
{
    public function show_client_message($client, $limit)
    {
        //显示留言数据

        self::where('client', '=', $client)
            ->where('status', 'in', [1, 2, 3])//现在状态为1,2,3的
            ->paginate($limit);
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