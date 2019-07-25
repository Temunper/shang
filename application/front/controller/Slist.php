<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 15:09
 */

namespace app\front\controller;

use think\Controller;

class Slist extends Controller
{
    public function slist()
    {


        $db = new \app\front\model\Slist();
        $re = $db->get_project_info();       //获得项目所有信息
        $clas = new Clas();
        $d_clas = $clas->get_all_clas();
        $ad_position = new AdPosition();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $this->assign('clas', $d_clas);    //返回分类
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        // dump($d_ad_position);die;
        $this->assign('list_info', $re);
        return $this->view->fetch('');

    }
}