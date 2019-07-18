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

class ThemeModel extends Model
{
    protected $table = 'theme';
    protected $autoWriteTimestamp = false;
    protected $resultSetType = 'collection';             //使对象可以直接转换为字符串数组
    protected $type = [
        'time' => 'timestamp',
    ];
    protected $insert = ['time'];

//    查询所有主题
    function get_all_theme($status)
    {
        $status?$c_status = "status = ". $status:$c_status = "status = 1";
        $result = Db::table($this->table)
            ->where($status)
            ->select()->each(function ($item,$key){
            if ($item['status'] == 1){
                $item['status'] = '正常';
            }else $item['status'] = '已删除';
            return $item;
        });;
        return $result;
    }

//根据id查询主题
    function get_theme_by_id($theme_id)
    {
        $result = Db::table($this->table)
            ->where('theme_id', '=', $theme_id)
            ->find();
        return $result;
    }

//添加主题
    function add_theme($theme)
    {
        $result = Db::table($this->table)
            ->insert($theme);
        return $result;
    }

//  模板自动转换
    function getTimeAttr($time)
    {
        return date('Y-m-d h:i:s', $time);
    }

    function setTimeAttr($time)
    {
        return time();
    }

//模型关联
    public function website()
    {
        return $this->hasMany('WebsiteModel','theme_id','theme_id',['theme'=>'theme','website'=>'website'],'right');
    }

}