<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/26
 * Time: 19:58
 */

namespace app\front\controller;


use think\Controller;
use think\Request;
use app\admin\model\WebsiteModel;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->getWebsite();
    }

    //获取当前站点
    public function getWebsite()
    {
        //1.当前站点信息
        $domain = Request::instance()->domain();

        $pos = strpos($domain, '/');
        $domain = substr($domain, $pos + 2);


        //2.查找数据库当前站点信息
        $model = WebsiteModel::get(['domain' => $domain, 'status' => 1]);

        //设置主页顶部
        !empty($model->logo) ? $logo = $model->logo : $logo = "/html/08sjw/picture/logo_top3.png";

        !empty($model->title) ? $headline = $model->title : $headline = "08商机网-创业找商机的好选择";
        !empty($model->keywords) ? $keywords = $model->keywords : $keywords = "08商机网,创业加盟网,加盟项目,连锁项目,创业资讯";
        !empty($model->description) ? $description = $model->description : $description = "08商机网是国内认可的优质加盟项目发布平台，汇聚众多创业项目和创业资讯，为创业者提供优质项目选择。找商机，上08商机网！";

        $title = [
            'title' => $headline,
            'keywords' => $keywords,
            'description' => $description];
        $this->assign('logo', $logo);
        $this->assign('title', $title);
    }


}