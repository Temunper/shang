<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/23
 * Time: 14:55
 */

namespace app\front\controller;

use app\front\model\ArticleModel;
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
        $base = new Index();
        $base->base_message();  //引入公共信息部分
        $one_article = $article_model->show_one_article($article_id);   //获得当前文章id信息
        // dump($d_article);die;
        $this->assign('one_article', $one_article);  //返回当前文章id 所有信息
        return $this->view->fetch('');

        /* return $this->fetch('/detail', ['article' => $re]);*/
    }

}