<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:40
 */

namespace app\front\controller;


use app\front\model\AdPositionModel;
use think\Controller;
use think\Request;

class AdPosition extends Base
{
    protected $ad_position_model = null;

    public function __construct(Request $request = null)
    {
        $this->ad_position_model = new AdPositionModel();
        parent::__construct($request);
    }

//    得到所有广告位的广告
    public function get_all_ad_position()
    {
        $result = $this->ad_position_model->get_all_ap_position();
        return $result;
    }

    //广告位的关注度+1
    public function add_attention($project_id)
    {
        $this->ad_position_model->add_attention($project_id);
    }

    //导航栏搜索框ajax模糊搜索
    public function ajax_select($param)
    {
        return $this->ad_position_model->ajax_select_like($param);
    }

    //随机获得9个广告位用于project页面
    public function get_rand()
    {
        return $this->ad_position_model->get_rand_project();
    }

}