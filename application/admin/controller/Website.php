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
        $website = $website_model->get_all_website($domain, $status)->toArray();
        $this->assign($website);
        return $this->fetch();
    }

//    修改内容
    public function update(Request $request)
    {
        $file = $request->file('file');
        if (!$file)
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(),'logo');
            if (mime_content_type($file) != ('image/gif' | 'image/png' | 'image/jpg')) {
                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
                echo json_encode($data);
            }
        }
        $website = WebsiteModel::getByDomain($request['domain']);
        if (!preg_match('/^1[34578]\d{9}$/', $request['phone'])) {
            $data = ['code' => 202, 'data' => '電話號碼格式不正確'];
            echo json_encode($data);
        }
        if (!$website) {
            $website = WebsiteModel::get($request['website_id']);
            $website->domain = $request['domain'];
            $website->theme_id = $request['theme_id'];
            if ($file_path) $website->logo = $file_path;
            $website->filing_number = $request['filing_number'];
            $website->company_name = $request['company_name'];
            $website->company_addr = $request['company_addr'];
            $website->phone = $request['phone'];
            $website->type = $request['type'];
            $website->keywords = $request['keywords'];
            $website->description = $request['description'];
            if ($website->isUpdate(true)->save()) {
                $data = ['code' => 200, 'data' => '更改成功'];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '更改失败'];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '域名已存在'];
            echo json_encode($data);
        }

    }

//    删除站点(更改站点状态)
    public function update_status(Request $request)
    {
        $website = $request['website'];
        $d_website = WebsiteModel::get($website['website_id']);
        $d_website->status = $website['status'];
        $d_website->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        echo json_encode($data);
    }

//    站点添加
    public function add(Request $request)
    {
        $file = $request->file('file');
        if (!$file)
            $file_path = $request->param('file_path');
        else {
            Upload::check($file);die;
//            if () {
//                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
//                echo json_encode($data);
//            }
            $file_path = Upload::file($file, $request->domain(),'logo');
        }
        $website = WebsiteModel::getByDomain($request['domain']);
        if (!preg_match('/^1[34578]\d{9}$/', $request['phone'])) {
            $data = ['code' => 202, 'data' => '電話號碼格式不正確'];
            echo json_encode($data);
        }
        if (!$website) {
            $website = new WebsiteModel();
            $website->domain = $request['domain'];
            $website->theme_id = $request['theme_id'];
            $website->logo = $file_path;
            $website->filing_number = $request['filing_number'];
            $website->company_name = $request['company_name'];
            $website->company_addr = $request['company_addr'];
            $website->phone = $request['phone'];
            $website->type = $request['type'];
            $website->keywords = $request['keywords'];
            $website->description = $request['description'];
            if ($website->save()) {
                $data = ['code' => 200, 'data' => '添加成功'];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '添加失败'];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '站点已存在'];
            echo json_encode($data);
        }
    }




//    文件验证
    public function check_file($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // 返回 mime 类型
        finfo_file($finfo, $file);
        finfo_close($finfo);
    }

    public function check_file2($file)
    {
        $file = fopen($file, "rb");
        $strFile = fread($file, 4); //只读文件头4字节
        fclose($file);
        $strInfo = @unpack("C4chars", $strFile);
        //dechex(),把十进制转换为十六进制。
        $code = dechex($strInfo ['chars1']) .
            dechex($strInfo ['chars2']) .
            dechex($strInfo ['chars3']) .
            dechex($strInfo ['chars4']);
        $type = '';
        switch ($code) //硬编码值查表
        {
            case "504b34" :
                $type = 'application/zip; charset=binary';
                break;
            case "89504e47" :
                $type = 'image/png; charset=binary';
                break;
            default :
                $type = false;
                break;
        }
        return $type;
    }
}