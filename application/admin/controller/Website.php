<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 9:44
 */

namespace app\admin\controller;


use app\admin\model\ThemeModel;
use app\admin\model\WebsiteModel;

class Website extends Base
{
    //查询所有站点
    public function website(){
      $theme = ThemeModel::get(1,'Website');
      $website = $theme->website;
      $website = $website->toArray();
      dump($website);die;
    }
//    修改内容

//    删除站点

//    站点添加

//   模糊域名搜索


}