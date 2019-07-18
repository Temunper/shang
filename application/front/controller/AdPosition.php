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

class AdPosition extends Controller
{
    protected $ad_position_model = null;

  public function __construct(Request $request = null)
  {
      $this->ad_position_model = new AdPositionModel();
      parent::__construct($request);
  }

//    得到所有广告位的广告
    public function get_all_ad_position(){
        $result = $this->ad_position_model->get_all_ap_position();
        return $result;
    }

}