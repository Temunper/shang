<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 15:09
 */

namespace app\front\controller;


use app\front\model\AdPositionModel;
use app\front\model\ClasModel;
use think\Controller;
use think\Request;


class Slist extends Controller
{
    public function slist()
    {
        $cl = new Clas();                                            //实例化分类对象
        $d_class = $cl->get_all_clas();
        $params = Request::instance()->param();
     //   dump($params);die;
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = '';
        isset($params['money']) ? $money = $params['money'] : $money = '';
        isset($params['area']) ? $area = $params['area'] : $area = '';   //获取地区
        isset($params['pro_name']) ? $name = $params['pro_name'] : $name = "";    //获取用户查询的用户名，模糊查询

        $class_id != 'f' ?: $class_id = '';         //判断是否为空值，替换f空值
        $money != 'f' ?: $money = '';
        $area != 'f' ?: $area = '';

        $default_class = $cl->get_class_by_id($class_id);   //通过父类id得到一级分类

        $c = [];
        $title = ClasModel::get($class_id);
        if ($title)
            $title = ['title' => $title->name . "项目大全", 'keywords' => $title->keywords, 'description' => $title->describe];   //返回网页的title等数据
        else
            $title = ['title' => "项目大全", 'keywords' => '', 'description' => ''];
        $search = ['money' => $money, 'area' => $area, 'class_id' => $class_id];           //返回搜索数据
        foreach ($d_class as $item) {                                  //当分类id是一级分类时遍历出所有树叶
            if ($item['class_id'] == $class_id && $item['son'] != null) {
                foreach ($item['son'] as $value) {
                    $c [] = $value['class_id'];
                }
                $class_id = $c;
                $class_id = implode(',', $class_id);                    //将数组转为字符串，并用‘,’隔开
            }
        }

        $adp_model = new AdPositionModel();
        $adp = AdPositionModel::all(function ($query) {
            $query->where("status", 1)->orderRaw('rand()')->limit(24);
        });


        if (empty($name)) {
            $ad_position = $adp_model->get_project_by_class($money, $class_id, $area);  //如果name 为空，则不是搜索，
        } else {
            //name 存在，则为用户搜索
            $ad_position = $adp_model->select_like_name($class_id, $name);  //模糊查询
            // dump($ad_position);die;
        }
        // dump($ad_position);die;
        $this->assign('title', $title);       //标题，keywords description
        $this->assign('search', json_encode($search));
        $this->assign('pro_name', $params['pro_name']);
        $this->assign('default_class', $default_class);
        $this->assign('clas', $d_class);
        $this->assign('ad_position', $ad_position);
        $this->assign('adp', $adp);
        return $this->view->fetch();
    }
}