<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:41
 */

namespace app\front\model;


use app\admin\common\Area;
use think\Db;
use think\Model;

class AdPositionModel extends Model
{
    protected $table = 'ad_position';

//    得到所有的广告位的广告
    function get_all_ap_position()
    {
        $result = Db::table($this->table)
            ->alias('ap')
//            ->join('project p','p.project_id = ap.project_id','left')
//            ->join('class c','c.class_id = p.class_id','left')
//            ->field('ap.name as name,ap.project_id,ap.image as image,c.class_id,c.name as class_name')
            ->where('ap.status', '=', '1')
            ->order('sort')
            ->paginate(24);
        return $result;
    }

//    分类广告位
    function get_project_by_class($money, $class_id, $area)
    {
        $c_status = "status = 1";         //其他条件
        if ($area != null) $area2 = (int)$area + 10000000;
        $area ? $and = "area between " . $area . " and " . $area2 : $and = null;
        $class_id ? $where = 'class_id in(' . $class_id . ')' : $where = null;
        $money ? $p_money = "money = " . $money : $p_money = null;
        $result = Db::table("view_adp")
            ->where($where)
            ->where($and)
            ->where($p_money)
            ->where($c_status)
            ->where("ad_id = 1041")
            ->order('sort','desc')
            ->paginate(24)->each(function ($item, $key) {
                $item['area'] = Area::getProvince($item['area']);
                return $item;
            });
        return $result;
    }
}