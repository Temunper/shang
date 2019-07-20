<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:41
 */

namespace app\customer\controller;

use app\customer\model\Article as ArticleModel;
use think\Request;
use app\customer\common\Upload;
use think\Session;

class Article extends Base
{
    //渲染客户端新增文章页面
    public function release_article()
    {

        $db = new ArticleModel();
        //查询当前用户所拥有的项目id，查询所有项目名称
        $project_info = "";

        $clinet_id = Session::get('client_id');

        $project_id = $db->check_project($clinet_id);

        if (!empty($project_id)) {
            $str = "";
            for ($i = 0; $i < count($project_id); $i++) {
                $str .= $project_id[$i]['project_id'] . ',';
            }
            $str = rtrim($str, ',');
            //查询所有项目名称
            $project_info = $db->select_project($str);
        } else {
            $project_info = "暂无项目，请联系客服发布项目";
        }

        return $this->fetch('', ['project_info' => $project_info]);
    }

    //发布文章
    public function do_release(Request $request)
    {
        //设置允许上传的图片类型
        $allow = [
            'image/gif',
            'image/png',
            'image/jpg',
            'image/jpeg'
        ];

        $status = 0;   //设置初始状态值
        $date_now = time();  //获得当前时间戳
        $data = $request->param(true);
        //设置验证规则
        $rule = [
            'type|类型' => 'egt:1',
            'title|标题' => 'require|length:2,20',
            'image|列图' => 'require',
            'brief|简介' => 'require|length:5,20',
            'content|内容' => 'require',
            'source|来源' => 'require',
        ];
        //设置错误返回信息
        $msg = [
            'type' => ['egt' => '项目类型必选'],
            'title' => ['require' => '标题不能为空',
                'length' => '标题长度需为2-20字符'],
            'image' => ['require' => '列图不能为空'],
            'brief' => ['require' => '简介不能为空',
                'length' => '简介需为5-20字符'],
            'content' => ['require' => '内容不能为空'],
            'source' => ['require' => '来源不能为空'],
        ];
        //执行验证
        $result = $this->validate($data, $rule, $msg);
        if ($result === true) {
            //通过验证，

            $type = $data['type'];
            if ($data['type'] == 1) {  //判断是否是项目类型 ，如果是，则判断是否存在项目id
                if (empty($data['project_id'])) {
                    return ['status' => $status, 'message' => '项目不能为空'];
                }
            }
            $project_id = !empty($data['project_id']) ? $data['project_id'] : "";
            $title = $data['title'];
            $file = $request->file('image');
            $brief = $data['brief'];
            $content = $data['content'];
            $source = $data['source'];
            $author = Session::get('client_id');
            //如果普通不为空，则保存图片,返回图片保存路径
            if (!$file)
                $file_path = $request->param('file_path');
            else {
                //判断上传图片是否在允许上传的类型$allow中
                if (!in_array($file->getInfo()['type'], $allow)) {
                    $data = ['code' => 202, 'data' => '上传文件格式不正确'];
                }
                $file_path = Upload::file($file, $request->domain(), 'artilce');
            }
            $info = [
                'type' => $type,
                'project_id' => $project_id,
                'title' => $title,
                'lis_img' => $file_path,
                'brief' => $brief,
                'content' => $content,
                'source' => $source,
                'author' => $author,
                'create_time' => $date_now,
                'update_time' => $date_now,
            ];
            //执行插入
            $db = new ArticleModel();
            $re = $db->add_artilce($info);
            if ($re) {
                //如果为真，则修改返回成功信息
                $status = 1;
                $result = "发布成功";
            } else {
                //为假，返回失败信息
                $result = "发布失败";
            }
        }
        return ['status' => $status, 'message' => $result];
    }


    //用户删除文章，传入文章id ，判断是否当前用户拥有的文章，修改文章状态为3
    public
    function delete_article(Request $request)
    {
        $article_id = $request->param('article_id') ? $request->param('article_id') : 0;
        //验证文章是否是当前用户所拥有的
        $own = $_SESSION['client_info']['name'];
        $db = new ArticleModel();
        $result = $db->check_own($own, $article_id);
        if ($result) {
            //为真，则修改文章状态为3
            $change = $db->change_status($article_id);
            if ($change) {
                return $this->return_info(1, '删除成功');
            }
            return $this->return_info(0, '非法失败');
        }
        return $this->return_info(0, '非法删除');
    }

//显示用户自己发布的文章
    public
    function show_self_article()
    {

        $author = Session::get('client_id') ? Session::get('client_id') : "";
        //查询作者为当前用户的文章

        $db = new ArticleModel();
        $auto_article = $db->select_self_article($author);
        // return dump($auto_article);
        if (empty($auto_article)) {
            $auto_article = "未发布过文章";
        }
        return $this->fetch('', ['article_info' => $auto_article]);
    }


}