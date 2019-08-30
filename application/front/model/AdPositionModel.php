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
            ->join('project p', 'p.project_id = ap.project_id', 'left')
//            ->join('class c','c.class_id = p.class_id','left')
            ->field('ap.*,p.money')
            ->where('ap.status', '=', '1')
            ->order('ap.sort')
            ->paginate(24);
        // dump($this->getLastSql());die;
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
        $result = Db::table($this->table)
            ->join('ad', 'ad.ad_id = ad_position.ad_id', 'left')
            ->join('project', 'project.project_id = ad_position.project_id', 'left')
            ->join('class', 'class.class_id = project.class_id', 'left')
            ->where($and)
            ->where($class_id)
            ->where($money)
            ->where($c_status)
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

    //模糊查询
    public function select_like_name($class_id, $name)
    {
        if (!empty($class_id)) {
            $clas = ['class_id' => $class_id];
        } else {
            $clas = null;
        }

        $result = Db::table($this->table)
            ->join('ad', 'ad.ad_id = ad_position.ad_id', 'left')
            ->join('project', 'project.project_id = ad_position.project_id', 'left')
            ->join('class', 'class.class_id = project.class_id', 'left')
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
            ->where('status', '=', 1)
            ->where($clas)
            ->where('name', 'like', '%' . $name . '%')
            ->order('sort', 'desc')
            ->paginate(24)->each(function ($item, $key) {
                $item['area'] = Area::getProvince($item['area']);
                return $item;
            });
        // dump($this->getLastSql());die;
        return $result;
    }

    //项目关注度+1
    public function add_attention($project_id)
    {
        Db::table($this->table)->where('project_id', $project_id)->setInc('attention');
    }


    //ajax 同步显示模糊搜索项目名
    public function ajax_select_like($params)
    {
        $name = $params['pro_name'];
        return Db::table($this->table)
            ->join('ad', 'ad.ad_id = ad_position.ad_id', 'left')
            ->join('project', 'project.project_id = ad_position.project_id', 'left')
            ->join('class', 'class.class_id = project.class_id', 'left')
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
            ->distinct(true)
            ->where('status', '=', 1)
            ->where($params['class_id'])
            ->where('name', 'like', '%' . $name . '%')
            ->order('sort', 'desc')
            ->column('name');
    }

    //随机获得9个广告位
    public function get_rand_project()
    {
        $result = Db::table($this->table)
            ->alias('ap')
            ->join('project p', 'p.project_id = ap.project_id', 'left')
//            ->join('class c','c.class_id = p.class_id','left')
            ->field('ap.*,p.money')
            ->where('ap.status', '=', '1')
            ->order('ap.sort')
            ->orderRaw('rand()')
            ->limit(9)
            ->select();
        return $result;
    }

}