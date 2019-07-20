<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 8:45
 */

namespace app\customer\controller;

use app\customer\model\IndexModel;
use think\Request;
use think\Session;

class Index extends Base
{
    //渲染客户后台主页
    public function index()
    {
        $this->is_login();  //判断用户是否登录
        //统计今天总留言
        $client_id = Session::get('client_id');
        $client_info = Session::get('client_info');
        //获得零点时间戳
        $time = strtotime(date('Ymd'));
        //获得现在的时间戳
        $time_now = time();

        $db = new IndexModel();
        $project_id = $db->check_project($client_id);  //根据当前拥用户client_id获得用户所有项目id
        $count = [];
        if (!empty($project_id)) {
            $str = "";
            for ($i = 0; $i < count($project_id); $i++) {
                $str .= $project_id[$i] . ',';
            }
            $str = rtrim($str, ',');
            $count = $db->check_count($str);
            $count = $count[0]['count(*)'];
        } else {
            $count = "未发布任何项目";
        }
        //拼接成字符串

        //查询时间段里的留言
        return $this->view->fetch('', [
            'count' => $count,
            'client_info' => $client_info,
        ]);
    }
}