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
        //设置返回状态值
        $status = 0;
        $result = ""; //设置初始返回信息
        $data = Request::instance()->param('search');  //提交按钮button 命名为search  如果提交中存在search 则为搜索事件，反正正常输出所有信息
        if (isset($data['search'])) {
            //存在search 执行搜索事件
            $data = array_filter($data);  //除去data数组中值为false的的项
            if (empty($data)) {
                //判断取出空项后的数组是否为空，为空，则返回提示信息
                $result = "请选择搜索信息";
                return ['status' => $result, 'message' => $result];
            }
            //存在索引，则执行搜索
            $article_info = $this->article_model->search_article($data);

        } else {
            //如果有ajax提交过来的搜索条件，则处理
            //显示所有文章，按照时间的排序
            $article_info = $this->article_model->all_article();
        }
        return $this->view->fetch('', ['article_info' => $article_info]);
    }

    //审批通过文章，修改状态
    public function approval_article()
    {
        //设置默认返回值，默认更新失败
        $code = 201;
        $results = "更新文章状态失败";
        $status = 2; //设置要修改的文章状态值
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
        $code = 201;
        $results = "删除文章失败";
        $status = 4;  //设置要修改的文章状态值
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


}