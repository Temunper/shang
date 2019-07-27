<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 8:45
 */

namespace app\customer\controller;

use app\customer\model\IndexModel;
use think\Session;

class Index extends Base
{
    //渲染客户后台主页
    public function index()
    {
//        $this->is_login();  //判断用户是否登录
        //统计今天总留言
        $client_id = Session::get('client_id');
        $client_info = Session::get('client_info');
        $db = new IndexModel();
        $project_id = $db->check_project($client_id);  //根据当前拥用户client_id获得用户所有项目id
        $project_id=implode(',',$project_id);//将数组拼接成用逗号分隔的字符串
        $count = $db->check_count($project_id);  //获得当前用户当天的所有项目相关留言总数
        //查询时间段里的留言
        return $this->view->fetch('', [
            'count' => $count,
            'client_info' => $client_info,
        ]);
    }
}