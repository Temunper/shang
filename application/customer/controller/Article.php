<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:41
 */

namespace app\customer\controller;

use app\customer\common\Upload;
use app\customer\model\Article as ArticleModel;
use think\Exception;
use think\Request;
use think\Session;

class Article extends Base
{
    protected $allow = [   // 定义图片上传的允许类型
        'image/gif',
        'image/png',
        'image/jpg',
        'image/jpeg'
    ];
    protected $model = null;

    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new ArticleModel();
    }

    //渲染客户端新增文章页面
    public function release_article()
    {


        //查询当前用户所拥有的项目id，查询所有项目名称
        $project_info = "";

        $clinet_id = Session::get('client_id');

        $project_id = $this->model->check_project($clinet_id);  //获取当前用户所有项目id
        if (empty($project_id)) {
            $project_info['project_id'] = "暂无项目";
        } else {
                $project_info=implode(',',$project_id   );

            $project_info = $this->model->select_project($project_info);   //得到所有项目信息
        }
        $this->assign('project_info', $project_info);
        return $this->fetch('');
    }

    //发布文章
    public function do_release(Request $request)
    {
        $status = 0;   //设置初始状态值
        $date_now = time();  //获得当前时间戳
        $data = $request->param(true);

        //执行验证
        $validate = 'app\customer\validate\ArticleValidate';
        $result = $this->validate($data, $validate);
        //验证结果不为真，则返回错误信息
        if ($result !== true) {
            return ['status' => $status, 'message' => $result];
        }
        //验证结果为真，继续执行
        //判断是否是项目类型 ，如果是，则判断是否存在项目id
        if ($data['type'] == 1) {
            if ($data['project_id'] == 0) {
                return ['status' => $status, 'message' => '具体项目不能为空'];
            }
        }
        $file = $request->file('image');
        $author = Session::get('client_id');
        //如果图片不为空，则保存图片,返回图片保存路径
        //如果图片为空
        if (!$file)
            $file_path = $request->param('file_path');
        else {
            //存在图片，则判断上传图片是否在允许上传的类型$allow中
            if (!in_array($file->getInfo()['type'], $this->allow)) {
                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
            }
            $file_path = Upload::file($file, $request->domain(), 'article');  //通过验证， 保存图片，返回路径
        }
        unset($data['image']);//删除data里的image字段
        unset($data['img']);
        $arr = [
            'lis_img' => $file_path,
            'author' => $author,
            'create_time' => $date_now,
            'update_time' => $date_now,
        ];
        $data = array_merge($data, $arr);  //合并数组
        //执行插入
        $re = $this->model->add_artilce($data);
        if ($re) {
            //如果为真，则修改返回成功信息
            $status = 1;
            $result = "发布成功";
        } else {
            //为假，返回失败信息
            $result = "发布失败";
        }
        //返回信息
        return ['status' => $status, 'message' => $result];
    }

    //用户删除文章，传入文章id ，判断是否当前用户拥有的文章，修改文章状态为3
    public function delete_article(Request $request)
    {
        $status = 0;
        $result = "删除失败";
        //接收article_id
        $article_id = $request->param('ids') ? $request->param('ids') : "";
        if (empty($article_id)) {
            return ['status' => $status, 'message' => '请选择要删除的文章'];
        }

        //验证文章是否是当前用户所拥有的
        $own = Session::get('client_id');
        //为真，通过验证，则修改文章状态为3
        $change = $this->model->change_status($article_id);
        if ($change) {
            //删除成功，修改状态值和返回信息
            $status = 1;
            $result = "删除成功";
        } else {
            //删除失败，修改返回信息
            $result = "删除失败";
        }
        return ['status' => $status, 'message' => $result];
    }

    //显示用户自己发布的文章 或精确搜索
    public function show_self_article()
    {
        $data = Request::instance()->post();
        //定于接收的数组
        $author = Session::get('client_id');
        // 获取当前用户所有项目信息
        $project_info = Message::get_client_project($author);
        //搜索用户的所有项目信息
        if (isset($data['search'])) {

            //存在 search 字段 则为精确搜索
            //取出时间并且删除该字段
            $time = $data['date1'] ? $data['date1'] : 0;  //如果为真则赋值，否则赋值0
            $time2 = $data['date2'] ? $data['date2'] : time();  //如果为真则赋值，否则赋值当前时间
            unset($data['date1']);
            unset($data['date2']);  //删除字段中的时间字段
            $data = array_filter($data);  //除去data数组中值为false(空)的的项
            $data = array_merge($data, ['author' => $author]);
            $status = !empty($data['status']) ? ($data['status']) : '1,2';  //
            unset($data['status']);  // 删除数组中的状态字段
            $article_info = $this->model->accurate_article($time, $time2, $data, $status);            //搜索相关文章
        } else {
            //查询作者为当前用户的文章

            $article_info = $this->model->select_self_article($author);  //查询当前用户所用文章
            // 如果返回值为空，则未查询到与当前用户相关的文章
            //渲染视图
        }
        return $this->fetch('', ['article_info' => $article_info,
            'project' => $project_info]);
    }

    //传入文章id ids渲染编辑修改用户的文章
    public function redact_article()
    {
        $data = Request::instance()->param();
        $article_id = $data[0] ? $data[0] : "";   //获取文章id
        if (empty($article_id)) {
            return ['status' => 0, 'message' => '请选择要编辑的文章'];
        }
        $client_id = Session::get('client_id');  //获取当前用户id
        $project_id = $this->model->check_project($client_id);  //获取当前用户所有项目id

        if (empty($project_id)) {
            $project_info['project_id'] = "暂无项目";   //如果不存在项目id，则显示暂无项目
        } else {
            $project_id = implode(',', $project_id);  //将数组转换成以逗号分隔的字符串
            $project_info = $this->model->select_project($project_id);   //得到所有项目信息
        }

        $type_id = $this->model->get_type($client_id, $article_id);   //得到当前文章类型
        $article_info = $this->model->check_own($client_id, $article_id);      //查询当前文章id的所有信息

        $this->view->assign('article_info', $article_info);  //当前要编辑的文章内容
        $this->view->assign('project_info', $project_info);  //用户所有的项目信息
        $this->view->assign('type_id', $type_id);         //当前文章原始信息 [类型]
        //渲染视图
        return $this->view->fetch('');
    }

    //执行用户编辑文章操作
    public function change_self_article(Request $request)
    {
        $status = 0;   //设置初始状态值
        $date_now = time();  //获得当前时间戳
        $data = $request->param(true);
        $file = $request->file('img');
        //执行验证
        $rule = [
            'type|类型' => 'require',
            'title|标题' => 'require|length:2,20',
            'brief|简介' => 'require|length:5,20',
            'content|内容' => 'require',
            'source|来源' => 'require',
            'keywords|关键词' => 'require |length:8,40',
            'description|描述' => 'require|length:20,160',
        ];
        $result = $this->validate($data, $rule);
        //验证结果不为真，则返回错误信息
        if ($result !== true) {
            return ['status' => $status, 'message' => $result]; //返回错误信息
        }
        //为真，继续执行
        $client_id = Session::get('client_id');  //获取当前用户id
        $data = array_merge($data, ['update_time' => time(), 'status' => 1]);  //添加更新时间字段和状态值status=》1
        //判断是否更换了新图片
        $file = $request->file('image');
        if (empty($file)) {
            //如果为空，则说明没有更换图片，
            unset($data['img']);
            unset($data['image']);  // 删除图片字段
            //更新数据
            $res = $this->model->update_article($data);
        } else {
            //不为空，存在新图片，执行上传图片操作，删除旧有图片
            //存在图片，则判断上传图片是否在允许上传的类型$allow中
            if (!in_array($file->getInfo()['type'], $this->allow)) {           //判断图片类型是否可上传
                $data = ['status' => 0, 'data' => '上传文件格式不正确'];
            }
            $file_path = Upload::file($file, $request->domain(), 'artilce');  //通过验证， 保存图片，返回路径
            $data = array_merge($data, ['lis_img' => $file_path]);  //合并成数组

            unset($data['image']);     //删除image 字段
            unset($data['img']);        //删除img 字段
            //更新数据
            $article_id = (int)$data['article_id'];
            $old_path = $this->model->get_image_path($article_id);  //获得旧图片的路径
            $res = "";
            try {
                $res = $this->model->update_article($data);   //执行更新
            } catch (Exception $e) {
                return $result = $e->getMessage();  // 失败返回信息
            }
        }
        if ($res) {
            //更新成功，修改返回状态值和返回信息
            $status = 1;
            $result = "更新成功";
        }
        return ['status' => $status, 'message' => $result]; //返回信息
    }

    //上传富文本框传来的图片
    public function edtior_upload()
    {
        $file = $this->request->file('file');  //获取上传文件
        $data['url'] = Upload::file($file, $this->request->domain(), 'article');  //获取返回路径
        if (!empty($data['url'])) {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }
        return json_encode($data);
    }

}