<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:06
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AdPositionModel extends Model
{
    protected $table = 'ad_position';
    protected $resultSetType = 'collection';
    protected $insert = ['status'];

//   返回页面
    function get_all_adp($status, $name, $sort, $ad_id, $project_id)
    {
        if (is_array($ad_id)) $ad_id = implode(',', $ad_id);        //若ad_id为数组则拼接成字符串
        $status ? $d_status = 'ad_position.status = ' . $status : $d_status = "ad_position.status = 1";
        $name ? $d_name = 'ad_position.name = ' . $name : $d_name = null;
        $ad_id ? $d_ad = 'ad_position.ad_id in (' . $ad_id . ')' : $d_ad = null;
        $project_id ? $project = 'ad_position.project_id = ' . $project_id : $project = null;
        switch ($sort) {                                    //选择排列顺序方式
            case 1:
                $order = 'ad_position.click_num';
                $down = 'desc';
                break;
            case 2:
                $order = 'ad_position.attention';
                $down = 'desc';
                break;
            default:
                $order = 'ad_position.sort';
                $down = 'asc';
                break;
        }
        $result = Db::table($this->table)
            ->join('ad', 'ad.ad_id = ad_position.ad_id', 'left')
            ->join('project', 'project.project_id = ad_position.project_id','left')
            ->join('class', 'class.class_id = project.class_id', 'left')
            ->where($d_status)
            ->where($d_name)
            ->where($d_ad)
            ->where($project)
            ->order($order, $down)
//            ->field('*')
            ->field('ad_position.ad_position_id AS ad_position_id,
        ad_position.ad_id AS ad_id,
        ad_position.project_id AS project_id,
        ad_position.name AS name,
        ad_position.image AS image,
        ad_position.abbr AS abbr,
        ad_position.sort AS sort,
        ad_position.status AS status,
        ad_position.click_num AS click_num,
        ad_position.attention AS attention,
        ad.name AS ad_name,
        project.name AS project_name,
        project.area AS area,
        project.class_id AS class_id,
        project.money AS money,
        class.name AS class_name')
            ->paginate(10)
            ->each(function ($item, $key) {
                if ($item['status'] == 1) {                                                   //将返回信息遍历，更改状态信息 ，1-》正常，2-》删除
                    $item['status'] = '正常';
                } else $item['status'] = '已删除';
                return $item;
            });
        return $result;
    }

    //    读取器和修改器
    public function getStatusAttr($value)
    {
        $status = [1 => '正常', 2 => '删除'];
        return $status[$value];
    }

}