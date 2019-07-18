<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 9:44
 */

namespace app\admin\controller;


use app\admin\model\ThemeModel;
use app\admin\model\WebsiteModel;
use think\Request;

class Website extends Base
{
    //查询所有站点
    public function website()
    {
        $request = Request::instance()->get();
        $website_model =new WebsiteModel();
        isset($request['domain']) ? $domain = $request['domain'] : $domain = null;
        isset($request['status'])? $status = $request['status']:$status = null;
        $website = $website_model->get_all_website($domain,$status);
        dump($website);die;
        $this->assign($website);
        return $this->fetch();
    }

//    修改内容
    public function update(Request $request)
    {

    }
//    删除站点(更改站点状态)
    public function update_status(Request $request){
        $website = $request['website'];
        $d_website = WebsiteModel::get($website['website_id']);
        $d_website->status = $website['status'];
        $d_website->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        echo json_encode($data);
    }

//    站点添加
     public function add(){

     }

}