<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/18
 * Time: 12:01
 */

namespace app\admin\controller;


use app\admin\model\ArticleModel;
use think\Request;

class Article extends Base
{
    protected $article_model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article_model = new ArticleModel();
    }

    //渲染文章页面，显示所有文章
    public function show_all_article()
    {
        $data = Request::instance()->param();
        //如果有ajax提交过来的搜索条件，则处理
        //显示所有文章，按照时间的排序
    }


}