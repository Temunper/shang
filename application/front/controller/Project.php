<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 16:30
 */

namespace app\front\controller;


use app\front\model\ProjectModel;
use think\Controller;
use think\Request;

class Project extends Controller
{
//  根据类型获得项目
    /*   public function get_project_by_class()
       {
           $result = $this->project_model->get_project_with_class();
           return $result;
       }*/

    public function project()
    {
        $code = 202;
        //接收传入的project_id 参数
        $data = Request::instance()->param('project_id');

        //根据project_id 查询所有相关信息
        if (empty($data)) {
            return json_encode(['code' => $code, 'msg' => '请选择要进入的项目']);
        }

        $base = new Index();
        $base->base_message();  //引入网页公共信息
        // 查询项目的所有相关信息
        $db = new ProjectModel();
        $project_info = $db->get_project_info($data);
        if (empty($project_info)) {
            return json_encode(['code' => 201, 'msg' => '不存在的项目，请检查']);   //查询数据为空，则返回错误信息
        }
        $this->assign('project_info', $project_info);    //返回当前项目的所有信息
        return $this->view->fetch();

    }



//    首页的生成树处理
//    public function get_index_pro($class)
//    {
//        $class_id = array();
//        $tree = array();
//        foreach ($class as $value) {
//            if (!empty($value['son'])) {
//                foreach ($value['son'] as $key=>$son) {
//                    if ($key == 0) {
//                        array_push($class_id, $son['class_id']);
//                    }
//                }
//            }
//        }
//       $project =  $this->get_project_by_class($class_id);     //得到顺序是所有第一个二级类的所有项目，再拼接回去
//       foreach ($class as $num=>$item){
//           if (!empty($value['son'])) {
//               foreach ($value['son'] as $key=>$son) {
//                   if ($key==0){
//                       foreach ($project as $pro) {
//                           $tree[$num] = array_merge($item, $class_id);
//                       }
//                   }
//               }
//           }
//       }
//    }
}