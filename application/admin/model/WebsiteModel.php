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

//   关联主题
//    public function theme()
//    {

//        return $this->hasOne('ThemeModel',$this->alias(['theme'=>'theme','website'=>'website']));
//    }

    function get_all_website($domain,$status){
        $domain?$where = "w.domain = like '%". $domain ."%'" :$where = null;
        $status?$w_status = "w.status = ". $status:$w_status = null;
        $result = Db::table($this->table)
            ->order('website_id','desc')
            ->alias('w')
            ->join('theme t','w.theme_id = t.theme_id','left')
            ->where($where)
            ->where($w_status)
            ->paginate(20)->each(function ($item,$key){
                if ($item['status'] == 1){
                    $item['status'] = '正常';
                }else $item['status'] = '已删除';
                return $item;
            });
        return $result;
    }
}