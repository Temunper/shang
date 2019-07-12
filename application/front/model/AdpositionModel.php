<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 10:41
 */

namespace app\front\model;


use think\Db;
use think\Model;

class AdpositionModel extends Model
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
            ->where('ap.status','=','1')
            ->order('sort')
            ->select();
        return $result;
    }
}