<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/12
 * Time: 16:32
 */

namespace app\front\controller;


use app\front\model\Article as ArticleModel;
use think\Controller;
use think\Request;

class Article extends Controller
{
    protected $article_model = null;

    public function __construct(Request $request = null)
    {
        $this->article_model = new ArticleModel();
        parent::__construct($request);
    }

//    得到所有的文章
    public function get_all_article()
    {
        $result = $this->article_model->get_all_article();
        return $result;
    }

    //渲染咨询页
    public function newslist()
    {
        $id = (int)Request::instance()->param('type');    //接收type 参数
        !empty($id) ? $type_id = (int)$id : $type_id = 1; //判断是否传入咨询类型id

        if ($type_id > 5 || $type_id < 1) {    //判断type 数值
            $type_id = 1;
        }
        //如果不存在article_id  则为正常显示所有数据

        $base = new \app\front\controller\Index();
        $base->base_message();  //引入公共信息
        $article = new ArticleModel();
        $article_info = $article->show_article($type_id);
        $this->assign('type', $type_id);
        $this->assign('article_info', $article_info);
        return $this->view->fetch('');
    }

    //根据项目id 查询项目所有的文章
    public function get_article($project_id)
    {
        return $this->article_model->get_some_article($project_id);
    }


}