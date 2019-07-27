<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 15:09
 */

namespace app\front\controller;


use app\admin\common\Area;
use app\front\model\AdPositionModel;
use app\front\model\ProjectModel;
use think\Controller;
use think\Request;


class Slist extends Controller
{
    public function slist()
    {
        $params = Request::instance()->param();
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = '';
        isset($params['money']) ? $money = $params['money'] : $money = '';                              //判断获取的数据，若无则赋值默认值
        isset($params['area']) ? $area = $params['area'] : $area = '';
        $cl = new Clas();                                                                                       //实例化分类对象
        $d_class = $cl->get_all_clas();
        $class_id != 'f' ?: $class_id = '';
        $money != 'f' ?: $money = '';
        $area != 'f' ?: $area = '';

        $default_class = $cl->get_class_by_id($class_id);
        $s_class = $class_id;
        $c = [];
        foreach ($d_class as $item) {                                                                               //当分类id是一级分类时遍历出所有树叶
            if ($item['class_id'] == $class_id && $item['son'] != null) {
                foreach ($item['son'] as $value) {
                    $c [] = $value['class_id'];
                }
                $class_id = $c;
                $class_id = implode(',', $class_id);                                                            //将数组转为字符串，并用‘,’隔开
            }
        }
        $search = ['money' => $money, 'area' => $area, 'class_id' => $s_class];
        $adp_model = new AdPositionModel();
        $adp = AdPositionModel::all(function ($query) {
            $query->where("status", 1)->orderRaw('rand()')->limit(24);
        });
        $ad_position = $adp_model->get_project_by_class($money, $class_id, $area);
        $this->assign('search', json_encode($search));
        $this->assign('default_class', $default_class);
        $this->assign('clas', $d_class);
        $this->assign('ad_position', $ad_position);
        $this->assign('adp', $adp);
        return $this->view->fetch();

    }
}