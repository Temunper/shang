<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 11:24
 */

namespace app\front\controller;


use app\front\model\ArticleModel;
use think\Controller;
use think\Db;
use think\Model;
use think\Request;

class Index extends Controller
{
//    首页
    public function index()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new Article();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
        return $this->fetch('/index');
    }

    //项目库
    public function slist()
    {
        $clas = new Clas();
        $d_clas = $clas->get_all_clas();
        $re = Db::query("select p.* ,ad.* from project p left join ad_position ad on p.project_id=ad.project_id where ad.status=1  order by ad.sort desc ");
        $this->assign('list_info', $re);
        $ad_position = new AdPosition();
        $article = new Article();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);
        return $this->fetch('/list');
    }

    //文章详情页
    public function detail()
    {

        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new Article();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类

        /*===============================================================*/
        //返回头部，搜索栏和尾栏数据

        $article_model = new ArticleModel();
        $id = Request::instance()->get('type');
        $article = Request::instance()->get('article_id');

        !empty($id) ? $type_id = (int)$id : $type_id = 1; //判断是否传入咨询类型id
        //!empty($article) ? $article_id = (int)$article : $article_id = ""; //判断是否传入文章id
        $article_id = 18;
        //id存在是，判断是否 id的值为1-5
        if ($type_id > 5 || $type_id < 1) {
            $type_id = 1;
        }
        $map = [
            'article_id' => $article_id,
            'type' => $type_id,
        ];
        if (empty($article_id)) {
            unset($map['article_id']);
        } else {
            unset($map['type']);
        }


        //  根据当前传入类型id返回咨询,主要信息
        $article_info = $article_model->show_article($map, 20);  //  根据当前传入类型id返回咨询

        //页面右边公告信息
        //最新项目公告
        $project_notice = $article_model->show_article(1, 10);
        $business_notice = $article_model->show_article(2, 10);
        $news_notice = $article_model->show_article(3, 10);
        $hot_notice = $article_model->show_article(4, 10);
        $business_story = $article_model->show_article(5, 10);

        $re = Db::query('select * from article where status=2');


        return $this->view->fetch('/detail', [
            'article_info' => $article_info,        //返回当前传入type_id 的主页信息
            'project_notice' => $project_notice,    //返回右侧公告项目咨询 信息
            'business_notice' => $business_notice,  //返回右侧公告创业咨询 信息
            'news_notice' => $news_notice,          //返回右侧公告新闻咨询 信息
            'hot_notice' => $hot_notice,            //返回右侧公告热门咨询 信息
            'business_story' => $business_story,    //返回右侧公告创业故事 信息
            'article' => $re,
        ]);

        /* return $this->fetch('/detail', ['article' => $re]);*/
    }

    //创业咨询
    public function newslist()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new Article();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类

        /*===============================================================*/
        //返回头部，搜索栏和尾栏数据

        $article_model = new ArticleModel();
        $id = Request::instance()->get('type');
        $article = Request::instance()->get('article_id');

        !empty($id) ? $type_id = (int)$id : $type_id = 1; //判断是否传入咨询类型id
        !empty($article) ? $article_id = (int)$article : $article_id = ""; //判断是否传入咨询类型id
        //id存在是，判断是否 id的值为1-5
        if ($type_id > 5 || $type_id < 1) {
            $type_id = 1;
        }
        $map = [
            'article_id' => $article_id,
            'type' => $type_id,
        ];
        if (empty($article_id)) {
            unset($map['article_id']);
        }

        //  根据当前传入类型id返回咨询,主要信息
        $article_info = $article_model->show_article($map, 20);  //  根据当前传入类型id返回咨询

        //页面右边公告信息
        //最新项目公告
        $project_notice = $article_model->show_article(1, 10);
        $business_notice = $article_model->show_article(2, 10);
        $news_notice = $article_model->show_article(3, 10);
        $hot_notice = $article_model->show_article(4, 10);
        $business_story = $article_model->show_article(5, 10);

        return $this->view->fetch('/newslist', [
            'article_info' => $article_info,        //返回当前传入type_id 的主页信息
            'project_notice' => $project_notice,    //返回右侧公告项目咨询 信息
            'business_notice' => $business_notice,  //返回右侧公告创业咨询 信息
            'news_notice' => $news_notice,          //返回右侧公告新闻咨询 信息
            'hot_notice' => $hot_notice,            //返回右侧公告热门咨询 信息
            'business_story' => $business_story,    //返回右侧公告创业故事 信息
        ]);
        //return $this->fetch('/newslist');
    }

    public function project()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new Article();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
        return $this->fetch('/project');
    }

//    设置分类
//    function set_class($d_clas, $d_ad_position, $class_num)
//    {
//        $class_data = array();
//        foreach ($d_clas as $value) {
//            foreach ($class_num as $num) {
//                if ($value['class_id'] == $num) {                            //类别号
//                    if (!empty($value['son'])) {
//                        foreach ($value['son'] as $item) {
//                            foreach ($d_ad_position as $data) {
//                                if ($data['class_id'] == $value['class_id'] || $data['class_id'] == $item['class_id']) {
//                                    $cl = ['f_class_id' => $value['class_id'], 'class_name' => $value['name']];
////                                    $cl['ad_p'] = $data;
//                                    $cl = array_merge($cl, $data);
//                                    $class_data[] = $cl;
//                                }
//                            }
//                        }
//                    } else {
//                        foreach ($d_ad_position as $data) {
//                            if ($data['class_id'] == $value['class_id']) {
//                                $cl = ['f_class_id' => $value['class_id'], 'class_name' => $value['name']];
////                                $cl['ad_p'] = $data;
//                                $cl = array_merge($cl, $data);
//                                $class_data[] = $cl;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        $data = array();
//        foreach ($class_num as $key => $num) {
//            foreach ($class_data as $value) {
//                if ($value['f_class_id'] == $num) {
//                    $data[$key][] = $value;
//                }
//            }
//        }
//        return $data;
//    }
}