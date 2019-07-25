<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:46
 */

namespace app\customer\controller;

use app\customer\common\Csv2;
use app\customer\model\Message as MessageModel;
use think\Request;
use think\Session;

class Message extends Base
{
    //客户查看自己的留言
    public function see_msg()
    {
        $data = Request::instance()->post();
        //dump($data);die;
        $db = new MessageModel();
        $client_id = Session::get('client_id'); //获得当前用户Id
        $project_info = $this->get_client_project($client_id);

        //拼接成字符串

        //如何存在search 字段，则处理页面上端搜索
        if (isset($data['search'])) {
            $time1 = $data['date1'] ? strtotime($data['date1']) : 0;
            $time2 = $data['date2'] ? strtotime($data['date2']) : time();
            unset($data['date1']);
            unset($data['date2']);   //删除数组中的时间字段
            $data = array_filter($data);  //删除字段值为空的字段
            //执行搜索，返回搜索信息
            $message_info = $db->check_exact($time1, $time2, $data);

        } else {
            //不存在search字段，则返回用户所有留言
            $project_id = $db->check_project($client_id);
            $message_info = $db->show_client_message($project_id);
        }
        //渲染查看留言视图
        return $this->fetch('', [
            'message_info' => $message_info,
            'project' => $project_info,
        ]);
    }


    //客户下载留言信息 ,传入要下载留言id数组
    public function down_message(Request $request)
    {
        $status = 0;
        $result = "";
        $data = $request->param('ids');  //获取要下载的文件id集
        //根据id查询信息
        $db = new MessageModel();
        $message_info = $db->to_download($data);
        //  dump($message_info);
        //设置下载文件的title
        $db->cheange_status($data);     //修改留言数据的状态
        $csv_title = array( '客户名称', '留言时间', '项目名称', '地区', '留言内容', '手机号');
        $csv = new Csv2();
        $res = $csv->put_csv($message_info, $csv_title);  //执行下载
        if (!$res) {
            //执行失败
            $result = "导出失败";
            return ['status' => $status, 'message' => $result];
        }
        //        //导出成功，修改留言数据状态=》2，已导出

        return true;
    }

    //用户删除留言，传入留言message_id
    public function delete_message(Request $request)
    {

        $status = 0;
        $result = "";
        $data = $request->param('ids');

        //删除留言
        $db = new MessageModel();
        $result = $db->delete_message_m($data);
        if ($result) {
            //执行成功，修改返回状态和返回信息
            $status = 1;
            $result = "删除成功";
        } else {
            $result = "删除失败";
        }
        return ['status' => $status, 'message' => $result];

    }

    //通过项目ip查询当天留言总数
    public function check_message_count($project_id)
    {
        $db = new MessageModel();
        return $db->check_count($project_id);
    }

    //通过用户id 查询用户所有项目信息
    public static function get_client_project($client_id)
    {
        $db = new MessageModel();
        $project_id = $db->check_project($client_id);  //获得当前用户所有项目id
        if (!empty($project_id)) {
            //通过项目id查询所有项目名，
            return $db->check_project_name($project_id); //返回给前端二级联动用
        } else {
            return [];
        }
    }

}