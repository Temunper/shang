<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 16:30
 */

namespace app\front\controller;


use app\admin\common\CUrl;
use app\front\model\Article as ArticleModel;
use app\front\model\ProjectModel;
use think\Request;


class Project extends Base
{
    protected $model = null;

    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new ProjectModel();
    }

    public function project()
    {
        $code = 202;
        //接收传入的project_id 参数
        $data = Request::instance()->param('project_id');
        /* dump($data);die;*/
        //根据project_id 查询所有相关信息
        if (empty($data)) {
            return json_encode(['code' => $code, 'msg' => '请选择要进入的项目']);
        }

        // 查询项目的所有相关信息
        $project_info = $this->model->get_project_info($data);
        if (empty($project_info)) {
            return json_encode(['code' => 201, 'msg' => '不存在的项目，请检查']);   //查询数据为空，则返回错误信息
        }
        //查询该项目相关文章
        $get = new ArticleModel();
        $article_info = $get->get_some_article($data);   //得到项目id 最新的10条项目咨询
        $title = $project_info['name'];
        $cdn = "http://cdn.hao987.cn/shophtml/" . $project_info['yw_name'] . "/item.html";
        $cdn = CUrl::curl($cdn);   //渲染cdn链接
        $project_info = array_merge(['cdn' => $cdn], $project_info);
        $title = ['title' => $project_info['name'], 'keywords' => $project_info['keywords'], 'description' => $project_info['description']];
        $re = $this->get_class_name($project_info['class_id']);
        $place[] = $re;

        if ($re['f_class_id'] != 0) {
            $res = $this->get_class_name($re['f_class_id']);
            $place[1]=$place[0];
            $place[0]  = $res;
        }
   //dump($place);die;
        $this->assign('title', $title);  //标题
        $this->assign('place',$place); //当前位置
        $this->assign('article_info', $article_info); //项目相关的项目咨询
        $this->assign('project_info', $project_info);    //返回当前项目的所有信息

        //  dump($article_info);die;
        //引入网页基本信息
        $base = new Index();
        $base->base_message();
        return $this->view->fetch();

    }

    public function get_class_name($class_id)
    {
        return $this->model->get_class_name_m($class_id);
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