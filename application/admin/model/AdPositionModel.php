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
    function get_all_adp($status, $name, $sort, $ad_id)
    {
        $status ? $d_status = 'status = ' . $status : $d_status = "status = 1";
        $name ? $d_name = 'name = ' . $name : $d_name = null;
        $ad_id ? $d_ad = 'ad_id in (' . $ad_id .')': $d_ad = null;
        switch ($sort) {
            case 1:
                $order = 'click_num';
                $down = 'desc';
                break;
            case 2:
                $order = 'attention';
                $down = 'desc';
                break;
            default:
                $order = 'sort';
                $down = 'asc';
                break;
        }
        $result = Db::table('view_adp')
            ->where($d_status)
            ->where($d_name)
            ->where($d_ad)
            ->order($order,$down)
            ->paginate(10)->each(function ($item,$key){
                if ($item['status'] == 1){
                    $item['status'] = '正常';
                }else $item['status'] = '已删除';
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