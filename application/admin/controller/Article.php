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

    //审批通过文章，修改状态
    public function approval_article()
    {
        //设置默认返回值，默认更新失败
        $code = 0;
        $results = "更新文章状态失败";
        $status = 2;
        //接收传入的article_id
        $data = Request::instance()->param('article_id');
        $article_id = $data ? (int)$data : 0;
        //执行修改

        $result = $this->article_model->change_status($article_id, $status);
        if ($result) {
            //执行成功，修改返回的状态值
            $code = 200;
            $results = "修改成功";
        }
        return ['code' => $code, 'msg' => $results];
    }

    //系统删除文章，修改文章状态值status=>4
    public function detele_article()
    {
        //设置默认返回值，默认更新失败
        $code = 0;
        $results = "删除文章失败";
        $status = 4;
        //接收传入的article_id
        $data = Request::instance()->param('article_id');
        $article_id = $data ? (int)$data : 0;
        //执行修改
        $result = $this->article_model->change_status($article_id, $status);
        if ($result) {
            //执行成功，修改返回的状态值
            $code = 200;
            $results = "删除成功";
        }
        return ['code' => $code, 'msg' => $results];
    }

    //文章查询功能
    public function query_article()
    {
        //根据 时间段，状态值，，
    }


}