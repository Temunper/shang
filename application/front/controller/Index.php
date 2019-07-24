<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 11:24
 */

namespace app\front\controller;


use app\front\model\Article as ArticleModel;
use think\Controller;

class Index extends Controller
{
//    首页
    public function index()
    {
        $this->base_message();          //引入网页公共信息
        return $this->view->fetch();
    }

    //项目库




    //返回网页公用信息
    public function base_message()
    {
        $clas = new Clas();
        $ad_position = new AdPosition();
        $article = new ArticleModel();
        $d_clas = $clas->get_all_clas();
        $d_ad_position = $ad_position->get_all_ad_position();        //得到广告位的所有广告
        $d_article = $article->get_all_article();
        $this->assign('article', $d_article);

      //  dump($d_article);die;
        $this->assign('ad_position', $d_ad_position);  //返回三个广告类的值
        $this->assign('clas', $d_clas);    //返回分类
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