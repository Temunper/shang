<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 14:55
 */

namespace app\front\controller;

use app\front\model\Article as ArticleModel;
use think\Controller;
use think\Request;

class Detail extends Controller
{
    //文章详情页
    public function detail()
    {

        $code = 202;

        /*===============================================================*/
        //返回头部，搜索栏和尾栏数据
        $article_model = new ArticleModel();
        $article_id = Request::instance()->param('article_id');
        if (empty($article_id)) {
            return ['code' => $code, 'msg' => '请选择要查看的文章'];
        }
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new ArticleModel();
        $d_clas = $clas->get_all_clas();    //获取二级分类，用于底部广告位
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
        $a = new Article();
        $a->get_ten_articles();

        $one_article = $article_model->show_one_article($article_id);   //获得当前文章id信息
       //dump($one_article);die;
        $title = ['title' => $one_article['title'], 'keywords' => $one_article['keywords'], 'description' => $one_article['description']];
        // dump($d_article);die;
        $this->assign('title', $title);
        $this->assign('one_article', $one_article);  //返回当前文章id 所有信息
        return $this->view->fetch('');

        /* return $this->fetch('/detail', ['article' => $re]);*/
    }

}