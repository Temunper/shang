<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 17:50
 */

namespace app\customer\common;

use think\Request;

class Upload
{
    /*
         * $name为表单上传的name值
         * $filePath为为保存在入口文件夹public下面uploads/下面的文件夹名称，没有的话会自动创建
         * $width指定缩略宽度
         * $height指定缩略高度
         * 自动生成的缩略图保存在$filePath文件夹下面的thumb文件夹里，自动创建
         * @return array 一个是图片路径，一个是缩略图路径，如下：
         * array(2) {
              ["img"] => string(57) "uploads/img/20171211\3d4ca4098a8fb0f90e5f53fd7cd71535.jpg"
              ["thumb_img"] => string(63) "uploads/img/thumb/20171211/3d4ca4098a8fb0f90e5f53fd7cd71535.jpg"
            }
         */
    public function uploadFile($name, $filePath, $width, $height)
    {
        $file = request()->file($name);
        if ($file) {
            $filePaths = ROOT_PATH . 'public' . DS . 'uploads' . DS . $filePath;
            if (!file_exists($filePaths)) {
                mkdir($filePaths, 0777, true);
            }
            $info = $file->move($filePaths);
            if ($info) {
                $imgpath = 'uploads/' . $filePath . '/' . $info->getSaveName();
                $image = \think\Image::open($imgpath);
                $date_path = 'uploads/' . $filePath . '/thumb/' . date('Ymd') . rand(10000, 99999);
                if (!file_exists($date_path)) {
                    mkdir($date_path, 0777, true);
                }
                $thumb_path = $date_path . '/' . $info->getFilename();
                $image->thumb($width, $height)->save($thumb_path);
                $data['img'] = $imgpath;
                $data['thumb_img'] = $thumb_path;
                return $data;
            } else {
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
        return false;
    }

    /**
     * @param $file  上传的文件
     * @param $domain  域名
     * @param $pack    文件夹名
     * @return mixed|null
     */
    public static function file($file, $domain, $pack)
    {
        $data=Request::instance()->param(true);
      //  dump($data);
        $upload_path = ROOT_PATH . 'public' . DS . 'upload' . DS . $pack;       //上传路径

        $photo_path = null;          //真实路径
        $local = ROOT_PATH . 'public';
        $info = null;
        if (!file_exists($upload_path)) {                 //如果目录不存在，则生成目录
            mkdir($upload_path, true);
        }
     //  dump($file);die;
        if (!empty($file)) {                          //文件不为空则将文件输出到uploads
            $info = $file->move($upload_path);
            if ($info) {
                $photo_path = $info->getRealPath();      //真实地址
//            http://local.study.cn/uploads/20190406/a1e131b66e45458b6bf50b69b5707d2b.png   //服务器地址
//            C:\dev\php_study\tp5\public\uploads\20190406  //本地地址

                $photo_path = str_replace($local, '', $photo_path);
                $photo_path = str_replace('\\', '/', $photo_path);
            }
        }
        return $photo_path;
    }
}