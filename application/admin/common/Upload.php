<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/18
 * Time: 18:10
 */

namespace app\admin\common;


class Upload
{
    public static function file($file, $domain, $pack)
    {
        $upload_path = ROOT_PATH . 'public' . DS . 'uploads' . DS . $pack;       //上传路径
        $photo_path = null;          //真实路径
        $local = ROOT_PATH . 'public';
        $info = null;
        if (!file_exists($upload_path)) {                 //生成目录
            mkdir($upload_path, true);
        }
        if (!empty($file)) {                          //文件不为空则将文件输出到uploads
            $info = $file->move($upload_path);
            if ($info) {
                $photo_path = $info->getRealPath();      //真实地址

//            http://local.study.cn/uploads/20190406/a1e131b66e45458b6bf50b69b5707d2b.png   //服务器地址
//            C:\dev\php_study\tp5\public\uploads\20190406  //本地地址

                $photo_path = str_replace($local, $domain, $photo_path);
                $photo_path = str_replace('\\', '/', $photo_path);
            }
        }
        return $photo_path;
    }
}