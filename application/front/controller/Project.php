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

    //渲染项目页
    public function project()
    {

        //1.获取项目id参数project_id
        $data = Request::instance()->param('project_id');
        //2.判断当前项目id是否存在
        if (empty($data)) {
            echo "请选择要查看的项目";
            return;
        }

        //3.根据project_id 查询所有相关信息
        $project_info = $this->model->get_project_info($data);
      //  dump($project_info);die;
        if (empty($project_info)) {
            echo "不存在的项目，请检查";
            return;
        }

        //4.查询该项目相关文章
        $get = new ArticleModel();
        $article_info = $get->get_some_article($data);   //得到项目id 最新的10条项目咨询

        // dump($article_info);die;
        //5.设置cdn文件路径并创建cdn对象
        $cdn = "http://cdn.hao987.cn/shophtml/" . $project_info['yw_name'] . "/item.html";
        $cdn = CUrl::curl($cdn);   //渲染cdn链接

        //6.将cdn链接拼接到项目信息数组
        $project_info = array_merge(['cdn' => $cdn], $project_info);
        //7.设置网站头部信息，
        $title = ['title' => $project_info['name'], 'keywords' => $project_info['keywords'], 'description' => $project_info['description']];

        //8.查找当前项目父类和二级类名称和id
        $re = $this->get_class_name($project_info['class_id']);
        $place[] = $re;
        if ($re['f_class_id'] != 0) {
            $res = $this->get_class_name($re['f_class_id']);
            $place[1] = $place[0];
            $place[0] = $res;
        }

        //9.声明变量
        $this->assign('title', $title);  //标题
        $this->assign('place', $place); //当前位置
        $this->assign('article_info', $article_info); //项目相关的项目咨询
        $this->assign('project_info', $project_info);    //返回当前项目的所有信息

        //10.引入网页基本信息，用于头部和底部模块
        $base = new Index();
        $base->base_message();

        //11.增加点击量+1
        $this->model->add_click_num($data);
        //12.渲染模板
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