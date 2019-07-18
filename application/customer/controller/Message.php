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
use app\customer\common\Csv;
use think\Session;

class Message extends Base
{
    //客户查看自己的留言
    public function see_msg(Request $request)
    {
        $data = Request::instance()->post();
        $db = new MessageModel();
        $client_id = Session::get('client_id'); //获得当前用户Id
        $project_id = $db->check_project($client_id);  //获得当前用户所有项目id
        //拼接成字符串
        if (!empty($project_id)) {
            //通过项目id查询所有项目名，
            $project_info = $db->check_project_name($project_id); //返回给前端二级联动用
        }
        if (isset($data['search'])) {
            $time1 = $data['date1'] ? strtotime($data['date1']) : 0;
            $time2 = $data['date2'] ? strtotime($data['date2']) : time();
            $name = !empty($data['name']) ? trim($data['name']) : "";
            $phone = !empty($data['phone']) ? trim($data['phone']) : "";
            $id = !empty($data['project_id']) ? $data['project_id'] : $project_id;
            $map = [
                'client' => $name,
                'phone' => $phone,
            ];
            if (empty($name)) {
                unset($map['client']);
            }
            if (empty($phone)) {
                unset($map['phone']);
            }
            $re = $db->check_exact($time1, $time2, $map, $id);
            // dump($re);die;
            return $this->view->fetch('', ['message_info' => $re, 'project' => $project_info]);

        }

        // return dump($str);
        $limit = $request->param('limit') ? $request->param('limit') : 15;
        $message_info = $db->show_client_message($project_id, $limit);
        //渲染查看留言视图
        return $this->fetch('', [
            'message_info' => $message_info,
            'project' => $project_info,
        ]);
    }


    //客户下载留言信息 ,传入要下载留言id数组
    public function down_message(Request $request)
    {
        $data = $request->param('message_id');
        $csv = new Csv();
        //根据id查询信息
        $db = new MessageModel();
        $message_info = $db->to_download($data);
        $csv_title = array('留言ID', '客户名称', '留言时间', '项目名称', '地区', '留言内容', '手机号', '状态');
        $csv = new Csv2();
        $csv->put_csv($message_info, $csv_title);
        //修改数据的状态
        $result = $db->cheange_status($data);

    }

    //用户删除留言，传入留言message_id
    public function delete_message(Request $request)
    {
        $status = 0;
        $data = $request->param('message_id');
        if (empty($data['message_id'])) {
            return '非法操作';
        }
        //删除留言
        $db = new Message();
        $result = $db->delete_message($data);
        if ($result) {
            return $this->return_info(1, '删除成功');
        }
        return $this->return_info(0, '删除失败');

    }


}