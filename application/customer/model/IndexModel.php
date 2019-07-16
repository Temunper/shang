<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/15
 * Time: 16:12
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class IndexModel extends Model
{
    public function check_project($client_id)
    {
        return Db::table('client_project')->field('project_id')
            ->where('client_id', $client_id)->select();
    }

    public function check_count($str, $time, $time_now)
    {
        //通过项目id 查找留言总数
        $sql = "select count(*) as total from message where project_id in ($str) and time BETWEEN $time and $time_now";
        return Db::query($sql);
    }
}
