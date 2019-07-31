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
    $type=[
        1=>'项目资讯',
        2=>'创业资讯',
        3=>'新闻资讯',
        4=>'热门专题',
        5=>'创业故事',
    ];
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new ArticleModel();
        $d_clas = $clas->get_all_clas();    //获取二级分类，用于底部广告位
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
        $this->get_ten_articles();  //查找5找文章类型的10条最新数据
        $article_info = $article->show_article($type_id);       //返回当前type类型的文章
        $this->assign('type_id', $type_id);
        $title=['title'=>$type[$type_id],'keywords'=>"",'description'=>""];       //标题关键字与描述
        $this->assign('title',$title);
        $this->assign('article_info', $article_info);
        $page= $article_info->render();
        $this->assign('page',$page);
        return $this->view->fetch('');
    }

    //根据项目id 查询项目所有的文章
    public function get_article($project_id)
    {
        return $this->article_model->get_some_article($project_id);
    }


    public function get_ten_articles()
    {
        $db = new ArticleModel();
        $article_p = $db->get_ten_article(1);
        $article_b = $db->get_ten_article(2);
        $article_n = $db->get_ten_article(3);
        $article_h = $db->get_ten_article(4);
        $article_s = $db->get_ten_article(5);
        $this->assign('article_p', $article_p);
        $this->assign('article_b', $article_b);
        $this->assign('article_n', $article_n);
        $this->assign('article_h', $article_h);
        $this->assign('article_s', $article_s);
    }

}