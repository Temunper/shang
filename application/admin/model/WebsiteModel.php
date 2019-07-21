<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 9:45
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class WebsiteModel extends Model
{

    protected $table = 'website';
    protected $resultSetType = 'collection';
    protected $insert = ['status'];
//   关联主题
//    public function theme()
//    {

//        return $this->hasOne('ThemeModel',$this->alias(['theme'=>'theme','website'=>'website']));
//    }

    function get_all_website($domain, $status)
    {
        $domain ? $where = "w.domain = like '%" . $domain . "%'" : $where = null;
        $status ? $w_status = "w.status = " . $status : $w_status = "w.status = 1";
        $result = Db::table($this->table)
            ->order('website_id', 'desc')
            ->alias('w')
            ->join('theme t', 'w.theme_id = t.theme_id', 'left')
            ->where($where)
            ->where($w_status)
            ->paginate(20)->each(function ($item, $key) {
                if ($item['status'] == 1) {
                    $item['status'] = '正常';
                } else $item['status'] = '已删除';
                if ($item['type'] == 2) $item['type'] = 'WAP';
                else $item['type'] = 'PC';
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