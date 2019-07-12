<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 16:30
 */

namespace app\front\controller;


use app\front\model\ProjectModel;
use think\Model;

class Project extends Model
{
    protected $project_model = null;

    public function __construct($data = [])
    {
        $this->project_model = new ProjectModel();
        parent::__construct($data);
    }

//  根据类型获得项目
    public function get_project_by_class()
    {
        $result = $this->project_model->get_project_with_class();
        return $result;
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