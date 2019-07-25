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
    protected $type = [                                 //用tp5模型时能替换掉type字段的时间戳
        'time' => 'timestamp:Y-m-d h-i-s',
    ];
    protected $insert = ['time', 'status'];


//    查询所有主题
    function get_all_theme($status)
    {
        $status ? $c_status = "status = " . $status : $c_status = "status = 1";
        $result = Db::table($this->table)
            ->where($status)
            ->paginate(1000000)->each(function ($item, $key) {      //只有分页方法有each方法
                if ($item['status'] == 1) {                                   //将返回信息遍历，更改状态信息 ，1-》正常，2-》删除
                    $item['status'] = '正常';
                } else $item['status'] = '已删除';
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

    //    读取器和修改器
    public function getStatusAttr($value)
    {
        $status = [1 => '正常', 2 => '删除'];
        return $status[$value];
    }


//模型关联
    public function website()
    {
        return $this->hasMany('WebsiteModel', 'theme_id', 'theme_id', ['theme' => 'theme', 'website' => 'website'], 'right');
    }                              //模型名                        //外键担当             //主键担当       别名                  别名              关联方式

}