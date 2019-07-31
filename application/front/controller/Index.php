<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 11:24
 */

namespace app\front\controller;

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
        $title = ['title' => "08商机网-创业找商机的好选择", 'keywords' => '08商机网,创业加盟网,加盟项目,连锁项目,创业资讯', 'description' => '08商机网是国内认可的优质加盟项目发布平台，汇聚众多创业项目和创业资讯，为创业者提供优质项目选择。找商机，上08商机网！'];
        $this->assign('title',$title);
        return $this->view->fetch();
    }

    //项目库


    //返回网页公用信息
    public function base_message()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new Article();
        $article->get_ten_articles();
        $d_clas = $clas->get_all_clas();
       // $o_clas=$clas->get_one_clas(18);
        //       // dump($o_clas);die;
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
    }



    public function test(){
        return $this->fetch("/test");
    }
}