<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 15:13
 */

namespace app\front\model;
use think\Db;

class Slist
{
    public function get_project_info()
    {
        $re = Db::query("select p.* ,ad.* from project p left join ad_position ad on p.project_id=ad.project_id where ad.status=1  order by ad.sort desc ");
        return $re;
    }
}