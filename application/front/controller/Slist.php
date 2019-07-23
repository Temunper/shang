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
        $base = new Index();
        $base->base_message();   //引入网页公共信息
        // dump($d_ad_position);die;
        $this->assign('list_info', $re);
        return $this->view->fetch('');

    }
}