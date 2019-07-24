<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/18
 * Time: 12:01
 */

namespace app\admin\controller;


use app\admin\model\Article as ArticleModel;
use think\Request;

class Article extends Base
{
    protected $article_model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->article_model = new ArticleModel();     //构造函数保存模型实例
    }

    //渲染文章页面，显示所有文章
    //显示用户自己发布的文章 或精确搜索
    public function article()
    {
        $data = Request::instance()->param();
        //判断是否存在search 字段，存在则为精确搜索
        if (isset($data['search'])) {
            $time = !empty($data['date1']) ? $data['date1'] : 0;  //如果为真则赋值，否则赋值0
            $time2 = !empty($data['date2']) ? $data['date2'] : time();  //如果为真则赋值，否则赋值当前时间
            $status = !empty($data['status']) ? $data['status'] : '1,2';
            unset($data['date1']);
            unset($data['date2']);
            unset($data['status']);
            $data = array_filter($data);  //除去data数组中值为false(空)的的项
            //搜索相关文章
            //   dump($data);die;
            $article_info = $this->article_model->accurate_article($time, $time2, $data, $status);
        } else {
            //查询作者为当前用户的文章
            $article_info = $this->article_model->select_all_article();  //查询当前用户所用文章
            // 如果返回值为空，则未查询到与当前用户相关的文章
            //渲染视图
        }
        //dump($article_info);die;
        return $this->fetch('', ['article_info' => $article_info]);
    }

    //审批文章，修改状态  传入id 和状态值
    public function approval_article()
    {
        $allow = [-1, 2];  //设置允许更改的状态值，驳回或通过
        //设置默认返回值，默认更新失败
        $code = 202;    //设置返回状态值，默认失败
        $results = "更新文章状态失败";   //设置返回信息
        //接收传入的article_id
        $data = Request::instance()->param();
     //   dump($data);die;
        if (empty($data['article_id'])) {
            return ['code' => $code, 'msg' => '请选择要审批的文章'];
        }
        if (empty($data['status']) || !in_array($data['status'], $allow)) {
            return ['code' => $code, 'msg' => '审批状态有误，请检查'];
        }

        //执行修改
        $result = $this->article_model->change_status($data['article_id'], $data['status']);
        if ($result) {
            //执行成功，修改返回的状态值
            $code = 200;
            $results = "修改成功";
        }
        return ['code' => $code, 'msg' => $results];
    }

    //系统删除文章，修改文章状态值status=>4
    public function delete_article()
    {
        //设置默认返回值，默认更新失败
        $code = 202;  //设置返回状态值，默认失败
        $results = "删除文章失败"; //设置返回信息
        $status = 4;  //设置要修改的文章状态值
        //接收传入的article_id
        $data = Request::instance()->param(['article_id','status']);
        dump($data);die;
        $article_id = $data ? $data : 0;
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