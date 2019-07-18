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
        $status ? $d_status = 'ad.status = ' . $status : $d_status = "status = 1";
        $name ? $d_name = 'ad.name = ' . $name : $d_name = null;
        $ad_id ? $d_ad = 'ad.ad_id in (' . $ad_id .')': $d_ad = null;
        switch ($sort) {
            case 1:
                $order = 'click_num';
                break;
            case 2:
                $order = 'attention';
                break;
            default:
                $order = 'sort';
                break;
        }
        $result = Db::table('view_adp')
            ->alias('ap')
            ->where($d_status)
            ->where($d_name)
            ->where($ad_id)
            ->join('ad_id')
            ->order($order)
            ->paginate(20)->each(function ($item, $key) {
                if ($item['status'] == 1) {
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

    public function setStatusAttr($value, $data)
    {
        return '正常' == $data['status'] ? 1 : 2;
    }
}