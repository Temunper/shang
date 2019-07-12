<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 11:41
 */

namespace app\customer\controller;

use app\customer\model\Article as ArticleModel;
use app\customer\validate\ArticleValidate;
use think\Request;
use app\customer\common\Upload;

class Article extends Base
{
    //渲染客户端新增文章页面
    public function release_article()
    {
        return $this->fetch();
    }

    //发布文章
    public function do_release(Request $request)
    {
        $data = $request->param();

        $type = $data['article_id'] ? $data['article_id'] : "";
        $project_id = $data['project_id'] ? $data['project_id'] : 0;
        $title = $data['title'] ? $data['title'] : "";
        $lis_img = $_FILES['image'] ? $_FILES['image'] : "";
        $brief = $data['brief'] ? $data['brief'] : "";
        $content = $data['content'] ? $data['content'] : "";
        $source = $data['source'] ? $data['source'] : "";
        $author = $data['author'] ? $data['author'] : "";
        if (empty($type)) {
            return $this->return_info(0, '类型不能为空');
        }
        if (empty($project_id)) {
            return $this->return_info(0, '项目不能为空');
        }
        if (empty($title)) {
            return $this->return_info(0, '标题不能为空');
        } elseif (strlen($title) < 2 || strlen($title) > 25) {
            return $this->return_info(0, '标题长度为2-25');
        }
        if (empty($lis_img)) {
            return $this->return_info(0, '图片不能为空');
        }
        if (empty($brief)) {
            return $this->return_info(0, '简介不能为空');
        }
        if (empty($content)) {
            return $this->return_info(0, '内容不能为空');
        }
        if (empty($source)) {
            return $this->return_info(0, '来源不能为空');
        }
        if (empty($author)) {
            return $this->return_info(0, '作者不能为空');
        }


        //   $validate = new ArticleValidate();
        // if ($validate->goCheck()) {
        //验证成功
        //保存图片,返回图片保存路径
        $img = new Upload();
        $file_path = $img->upload_file($lis_img);


        $data_info = [
            'type' => $data['type'],
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'lis_img' => $file_path,
            'brief' => $data['brief'],
            'content' => $data['content'],
            'source' => $data['source'],
            'author' => $data['author']//$_SESSION['client_id'],
        ];
        $db = new ArticleModel();
        $result = $db->insert($data_info);
        if ($result) {
            return $this->return_info(1, '添加成功');
        }
        return $this->return_info(0, '添加失败，请检查');
    }
    //return $this->return_info(0, '验证失败，请检查');


//用户删除文章，传入文章id ，判断是否当前用户拥有的文章，修改文章状态为3
    public function delete_article(Request $request)
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
    public function show_self_article()
    {
        $author = 17; //$_SESSION['client_id'] ? $_SESSION['client_id']  : "";
        //查询作者为当前用户的文章
        $db = new ArticleModel();
        $auto_article = $db->select_self_article($author);
        $this->assign('article_info', $auto_article);

        return $this->fetch();


    }


}