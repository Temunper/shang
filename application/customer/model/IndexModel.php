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
        return Db::table('client_project')
            ->where('client_id', $client_id)->column('project_id');

    }

    public function check_count($project_id)
    {

        $time_old = strtotime(date('Ymd'));  //当天零点的时间戳
        $time_new = time(); // 当前时间的时间戳
        //通过项目id 查找留言总数
        $re = Db::query('select count(*) from message where project_id in (?) 
                               and time between ? and ?', [$project_id, $time_old, $time_new]);
        return $re;
    }
}
