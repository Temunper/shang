<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/18
 * Time: 18:10
 */

namespace app\admin\common;


use think\Controller;
use think\File;
use think\Validate;

class Upload
{
    public static function file($file, $domain, $pack)
    {
        $upload_path = ROOT_PATH . 'public' . DS . 'upload' . DS . $pack;       //上传路径
        $photo_path = null;          //真实路径
        $local = ROOT_PATH . 'public';   //应用的public文件
        $info = null;
        if (!file_exists($upload_path)) {                 //生成目录
            mkdir($upload_path, true);
        }
        if (!empty($file)) {                          //文件不为空则将文件输出到uploads
            $info = $file->validate(['ext'=>'jpg,png,gif'])->move($upload_path,'');  //判断文件类型
            if ($info) {
                $photo_path = $info->getRealPath();      //真实地址

//            http://local.study.cn/uploads/20190406/a1e131b66e45458b6bf50b69b5707d2b.png   //服务器地址
//            C:\dev\php_study\tp5\public\uploads\20190406  //本地地址

                $photo_path = str_replace($local, $domain, $photo_path);    //地址转换（本地，域名，上传图片的路径）-》服务器地址
                $photo_path = str_replace('\\', '/', $photo_path); //替换成斜杠
                return $photo_path;
            }else{
               return "false";
            }
        }

    }

//    判断文件类型
    public static function check($file)
    {
        $finfo = finfo_open(FILEINFO_MIME); // 返回 mime 类型
        var_dump(finfo_file($finfo, $file));
        finfo_close($finfo);
    }
}