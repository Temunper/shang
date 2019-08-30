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


class Slist extends Base
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
            $title = ['title' => "创业项目推荐_热门项目行业分类-08商机网", 'keywords' => '创业优质项目,热门项目,什么项目赚钱', 'description' => '汇聚特色餐饮项目，集成墙，全铝家居，轻钢别墅，制砂机，垃圾处理器，中空模板等热门行业项目，帮助创业者提供各行业创业商机！'];
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

        $this->assign('title', $title);       //标题，keywords description
        $this->assign('search', json_encode($search));
        $this->assign('pro_name', $params['pro_name']);
        $this->assign('default_class', $default_class);
        $index = new Index();
        $this->assign('ad_position_list', $ad_position);  //项目列表
        $index->base_message();
//        $this->assign('clas', $d_class);

        $this->assign('adp', $adp);
        return $this->fetch();
    }

    public function ajax_select()
    {
        //1.获取上传信息
        $data = Request::instance()->only('class_id,pro_name');
        //2.判断class_id
        if (!empty($data['class_id'])) {
            $data['class_id'] != 'f' ?: $data['class_id'] = "";
        }

        $ad_model = new AdPosition();
        $result = $ad_model->ajax_select($data);

        return $result;
    }
}