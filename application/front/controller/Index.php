<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 11:24
 */

namespace app\front\controller;

use app\front\model\AdModel;
use app\front\model\AdPositionModel;
use think\Request;

class Index extends Base
{
//    首页
    public function index()
    {
        $this->base_message();          //引入网页公共信息
        $params = Request::instance()->param();
        isset($params['money']) ? $money = $params['money'] : $money = '';  //导航栏下金额
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = '';  //父类选择
        $searchs = ['money' => $money, 'class_id' => $class_id];
        return $this->view->fetch();
    }

    //项目库


    //返回网页公用信息
    public function base_message()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $ad = AdModel::all(function ($query) {
            $query->where('status', 1);
        })->toArray();
        $article = new Article();
        $article->get_ten_articles();
        $d_clas = $clas->get_all_clas();
        // $o_clas=$clas->get_one_clas(18);
        //       // dump($o_clas);die;
        $d_ad_position = AdPositionModel::all(function ($query) {
            $query->where('status', 1);
        })->toArray();        //得到广告位的所有广告
        $adp = [];
        foreach ($d_ad_position as $value) {
            foreach ($ad as $item) {
                if ($item['ad_id'] == $value['ad_id']) {
                    $adp[$item['ad_id']][] = $value;
                }
            }
        }
        $this->assign('ad_position', $adp);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
    }


    public function test()
    {
        return $this->fetch("/test");
    }
}