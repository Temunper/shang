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


    //显示用户自己发布的文章 或精确搜索
    public function show_self_article()
    {
        //1.接收上传信息
        $data = Request::instance()->post();
        //2.获取当前用户id
        $author = Session::get('client_id');
        // 3.获取当前用户所有项目信息
        $project_info = Message::get_client_project($author);
        //4.判断是精确查询 还是普通搜索用户的所有文章信息
        if (isset($data['search'])) {
            //存在 search 字段 则为精确搜索
            //取出时间并且删除该字段
            $time = $data['date1'] ? strtotime($data['date1']) : 0;  //如果为真则赋值，否则赋值0
            $time2 = $data['date2'] ? strtotime($data['date2']) : time();  //如果为真则赋值，否则赋值当前时间
            //判断$time 是否小于$time2
            if ($time > $time2) {   //如果$time 大于$time2  则交换时间位置
                $time3 = $time;
                $time = $time2;
                $time2 = $time3;
            }
            unset($time3);
            unset($data['date1']);
            unset($data['date2']);  //删除字段中的时间字段
            $data = array_filter($data);  //除去data数组中值为false(空)的的项
            $data = array_merge($data, ['author' => $author]);  //拼接用户id
            $status = !empty($data['status']) ? ($data['status']) : '-1,1,2';  //
            unset($data['status']);  // 删除数组中的状态字段
            $article_info = $this->model->accurate_article($time, $time2, $data, $status);            //搜索相关文章
        } else {
            //查询作者为当前用户的文章
            $article_info = $this->model->select_self_article($author);  //查询当前用户所用文章
            // 如果返回值为空，则未查询到与当前用户相关的文章
        }
        //5.定义数据为空时返回的信息
        $empty = "<span style='color: red'>暂未有相关文章</span>";
        //6.声明变量
        $this->assign('empty', $empty);
        $this->assign('article_info', $article_info);
        $this->assign('project', $project_info);
        //7.渲染视图
        return $this->fetch();
    }

    //渲染客户端新增文章页面
    public function release_article()
    {
        //1.获取当前用户id
        $client_id = Session::get('client_id');

        //2.根据当前用户获得其所有项目id
        $project_id = $this->model->check_project($client_id);
        $project_info = implode(',', $project_id);
        $project_info = $this->model->select_project($project_info);   //得到所有项目信息

        //3.设置变量
        $this->assign('project_info', $project_info);
        return $this->fetch('');
    }

    //发布文章
    public function do_release()
    {
        $status = 0;   //设置初始状态值
        $date_now = time();  //获得当前时间戳
        //1.获取传入信息
        $data = Request::instance()->param(true);

        //2.执行验证
        $validate = 'app\customer\validate\ArticleValidate';
        $result = $this->validate($data, $validate);
        //验证结果不为真，则返回错误信息
        if ($result !== true) {
            return ['status' => $status, 'message' => $result];
        }
        //验证结果为真，继续执行
        //3.判断是否是项目类型 ，如果是，则判断是否存在项目id
        if ($data['type'] == 1) {
            if ($data['project_id'] == 0) {
                return ['status' => $status, 'message' => '具体项目不能为空'];
            }
        }
        //4.获取上传文件信息，并上传至服务器
        $file = Request::instance()->file('image');
        $author = Session::get('client_id');
        //如果图片不为空，则保存图片,返回图片保存路径
        if (!$file)
            //如果不是图片，则获取路径
            $file_path = Request::instance()->param('file_path');
        else {
            //如果是图片，则判断上传图片是否在允许上传的类型$allow中
            if (!in_array($file->getInfo()['type'], $this->allow)) {
                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
            }
            //通过验证，执行上传图片，返回路径
            $file_path = Upload::file($file, Request::instance()->domain(), 'article');
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
        //5.执行新增操作
        $re = $this->model->add_artilce($data);
        if ($re) {
            //如果为真，则修改返回成功信息
            $status = 1;
            $result = "发布成功";
        } else {
            //为假，返回失败信息
            $result = "发布失败";
        }
        //6.返回信息
        return ['status' => $status, 'message' => $result];
    }

    //用户删除文章，传入文章id ，判断是否当前用户拥有的文章，修改文章状态为3
    public function delete_article()
    {
        //0.设置默认返回信息
        $status = 0;
        $result = "删除失败";
        //1.接收文章id参数 article_id
        $article_id = Request::instance()->param('ids') ? Request::instance()->param('ids') : "";
        if (empty($article_id)) {
            return ['status' => $status, 'message' => '请选择要删除的文章'];
        }

        //2.验证文章是否是当前用户所拥有的
        $own = Session::get('client_id');

        //3.为真，通过验证，则修改文章状态为3
        $change = $this->model->change_status($article_id);
        if ($change) {
            //删除成功，修改状态值和返回信息
            $status = 1;
            $result = "删除成功";
        } else {
            //删除失败，修改返回信息
            $result = "删除失败";
        }
        //4.返回信息
        return ['status' => $status, 'message' => $result];
    }


    //传入文章id ids渲染编辑修改用户的文章
    public function redact_article()
    {
        //1.获取上传的文章id
        $data = Request::instance()->param('article_id');
        //2.验证文章id
        $article_id = $data ? $data : "";   //获取文章id
        if (empty($article_id)) {
            return ['status' => 0, 'message' => '请选择要编辑的文章'];
        }
        //3.获取当前用户id 和当前用户所有项目id
        $client_id = Session::get('client_id');
        $project_id = $this->model->check_project($client_id);

        if (empty($project_id)) {
            $project_info['project_id'] = "暂无项目";   //如果不存在项目id，则显示暂无项目
        } else {
            $project_id = implode(',', $project_id);  //将数组转换成以逗号分隔的字符串
            $project_info = $this->model->select_project($project_id);   //得到所有项目信息
        }

        $type_id = $this->model->get_type($client_id, $article_id);   //得到当前文章类型
        $article_info = $this->model->check_own($client_id, $article_id);      //查询当前文章id的所有信息

        //4.声明变量
        $this->view->assign('article_info', $article_info);  //当前要编辑的文章内容
        $this->view->assign('project_info', $project_info);  //用户所有的项目信息
        $this->view->assign('type_id', $type_id);         //当前文章原始信息 [类型]

        //5.渲染视图
        return $this->view->fetch('');
    }

    //执行用户编辑文章操作
    public function change_self_article(Request $request)
    {
        //0.设置初始状态值
        $status = 0;
        $date_now = time();  //获得当前时间戳

        //1.获取上传信息
        $data = $request->param(true);
        $file = $request->file('img');

        //2.执行验证
        $rule = [
            'type|类型' => 'require',
            'title|标题' => 'require|length:2,20',
            'brief|简介' => 'require|length:5,20',
            'content|内容' => 'require',
            'source|来源' => 'require',
            'keywords|关键词' => 'require|length:8,40',
            'description|描述' => 'require|length:20,160',
        ];
        $result = $this->validate($data, $rule);

        //3.验证结果不为真，则返回错误信息
        if ($result !== true) {
            return ['status' => $status, 'message' => $result]; //返回错误信息
        }

        //4.通过验证，继续执行
        $client_id = Session::get('client_id');  //获取当前用户id
        $data = array_merge($data, ['update_time' => time(), 'status' => 1]);  //添加更新时间字段和状态值status=》1

        //5.判断是否更换了新图片
        $file = $request->file('image');
        if (empty($file)) {
            //如果为空，则说明没有更换图片，
            unset($data['img']);
            unset($data['image']);  // 删除图片字段
            //5.1更新数据
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
        //6.返回信息
        return ['status' => $status, 'message' => $result]; //返回信息
    }

    //上传富文本框传来的图片
    public function edtior_upload()
    {
        //1.获取上传文件
        $file = $this->request->file('file');

        //2. 验证是否允许上传的类型
        if (!in_array($file->getInfo()['type'], $this->allow)) {
            $data = ['code' => 202, 'data' => '上传文件格式不正确'];
        }

        //3.通过验证，执行上传图片，返回路径
        $data['url'] = Upload::file($file, $this->request->domain(), 'article');  //获取返回路径
        if (!empty($data['url'])) {
            $data['error'] = 0;
        } else {
            $data['error'] = 1;
        }
        //4.返回图片路径
        return json_encode($data);
    }

}