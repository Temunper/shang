<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:05
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AdModel extends Model
{
    protected $table = 'ad';
    protected $insert = ['status'];
    protected $resultSetType = 'collection';


//    查询所有广告位
    function get_all_ad($status, $theme_id, $name)
    {
        $name ? $a_name = "a.name = " . $name : $a_name = null;
        $status ? $a_status = "a.status = " . $status : $a_status = "a.status = 1";
        $theme_id ? $t_theme_id = "t.theme_id " . $theme_id : $t_theme_id = null;
        $result = Db::table($this->table)
            ->alias('a')
            ->join('theme t', 'a.theme_id = t.theme_id', 'left')
            ->where($a_status)
            ->where($t_theme_id)
            ->where($a_name)
            ->field('a.ad_id,a.name,a.theme_id,a.image,a.status,t.name as theme_name')
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
}