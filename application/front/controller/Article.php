<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/12
 * Time: 16:32
 */

namespace app\front\controller;


use app\front\model\ArticleModel;
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
//    文章分类
    public function set_article_class($data)
    {

    }
}