<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/13
 * Time: 16:45
 */

namespace app\admin\controller;


use app\admin\model\ProjectModel;
use think\Request;


class Project extends Base
{
    protected $project_model = null;

    public function __construct(Request $request = null)
    {
        $this->project_model = new ProjectModel();
        parent::__construct($request);
    }

//    得到项目列表，可以分类
    public function show_project()
    {
        $params = Request::instance()->get();
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = null;
        $result = $this->project_model->get_project_by_class($class_id);
        return $result;
    }

    //添加项目
    public function add_project()
    {
        $params = Request::instance()->get();
        $project = $this->check_project($params);



    }
    //    项目验证
    public function check_project($project){

    }
}


























