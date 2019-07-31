<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/26
 * Time: 19:58
 */

namespace app\front\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->set_empty();
    }

    public function set_empty()
    {
        $empty = [
            'title' => '最新创业资讯-08商机网',
            'keywords' => '最新创业资讯, 行业动态新闻, 创业指南, 加盟市场分析',
            'description' => '发布最新热门创业资讯、行业动态趋势、加盟市场分析等主题资讯，帮助创业者了解最新创业动态，掌握最新创业商机'
        ];
        $this->assign('empty', $empty);
    }


}