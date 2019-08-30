<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 9:44
 */

namespace app\admin\controller;


use app\admin\common\Upload;
use app\admin\model\ThemeModel;
use app\admin\model\WebsiteModel;
use think\Request;

class Website extends Base
{
    //查询所有站点
    public function website()
    {
        $request = Request::instance()->get();
        $website_model = new WebsiteModel();
        isset($request['domain']) ? $domain = $request['domain'] : $domain = null;
        isset($request['status']) ? $status = $request['status'] : $status = null;
        $website = $website_model->get_all_website($domain, $status);
        $this->assign('website', $website);
        return $this->fetch();
    }

//  添加站点
    public function plus()
    {
        $theme = ThemeModel::all();
        $this->assign('theme', $theme->toArray());
        return $this->fetch("website/add");
    }

//    站点内容
    public function content()
    {
        $request = Request::instance()->param();
        $theme = ThemeModel::all();
        $website = WebsiteModel::get($request['website_id']);
        $this->assign('website', $website->toArray());
        $this->assign('theme', $theme->toArray());
        return $this->fetch();
    }

//    修改内容
    public function update(Request $request)
    {
        $file = $request->file('file');
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'website');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $d_website = WebsiteModel::getByDomain($request->param('domain'), function ($query) {       //根据域名查询返回对象
            $query->where('status', 1);
        });
        dump($d_website->toArray());die;
        $website = WebsiteModel::get($request->param('website_id'));                                //根据返回id获得对象
        if (!$d_website || $request->param('domain') == $website->domain) {                         //判断站点是否已经存在
            $website->domain = $request->param('domain');
            $website->theme_id = $request->param('theme_id');
            if ($file_path) $website->logo = $file_path;
            $website->filing_number = $request->param('filing_number');
            $website->company_name = $request->param('company_name');
            $website->company_addr = $request->param('company_addr');
            $website->phone = $request->param('phone');
            $website->title = $request->param('title');
            $website->type = $request->param('type');
            $website->keywords = $request->param('keywords');
            $website->description = $request->param('description');
            $validate = $this->validate($website->toArray(), 'Website');                            //验证更新信息
            if ($validate === true && $website->isUpdate(true)->save()) {                            //根据验证结果更新
                $data = ['code' => 200, 'data' => '更改成功'];
                return json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '更改失败,' . $validate];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '域名已存在'];
            return json_encode($data);
        }

    }

//    删除站点(更改站点状态)
    public function update_status()
    {
        $request = Request::instance()->param();
        $d_website = WebsiteModel::get($request['website_id']);
        $d_website->status = 2;
        $d_website->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        return json_encode($data);
    }

//    站点添加
    public function add(Request $request)
    {
        $file = $request->file('file');                             //同理于修改方法update()
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'website');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $website = WebsiteModel::getByDomain($request->param('domain'), function ($query) {
            $query->where('status', 1);
        });
        $param = special_filter($request->param());
        if (!$website) {
            $website = new WebsiteModel();
            $website->domain = $param['domain'];
            $website->title = $param['title'];
            $website->theme_id = $param['theme_id'];
            $website->logo = $file_path;
            $website->filing_number = $param['filing_number'];
            $website->company_name = $param['company_name'];
            $website->company_addr = $param['company_abbr'];
            $website->phone = $param['phone'];
            $website->type = $param['type'];
            $website->keywords = $param['keywords'];
            $website->description = $param['description'];
            $website->status = 1;
            $validate = $this->validate($website->toArray(), 'Website');
            if ($validate === true && $website->save()) {
                $data = ['code' => 200, 'data' => '添加成功'];
                return json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '添加失败，' . $validate];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '站点已存在'];
            return json_encode($data);
        }
    }
}