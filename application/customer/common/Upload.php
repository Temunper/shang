<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 17:50
 */

namespace app\customer\common;

use \think\Image;

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


    function upload_file($fileInfo, $upload = "./upload", $imagesExt = ['gif', 'png', 'jpg'])
    {

        if ($fileInfo['error'] === 0) {

            $ext = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $imagesExt)) {

                return "文件非法类型";

            }

            if (!is_dir($upload)) {

                mkdir($upload, 0777, true);

            }

            $fileName = md5(uniqid(microtime(true), true)) . "." . $ext;

            $destName = $upload . "/" . $fileName;

            if (!move_uploaded_file($fileInfo['tmp_name'], $destName)) {

                return "文件上传失败！";

            }
            //返回文件路径名称
            return $destName;

        } else {

            switch ($fileInfo['error']) {

                case 1:

                    echo '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';

                    break;

                case 2:

                    echo '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';

                    break;

                case 3:

                    echo '文件只有部分被上传';

                    break;

                case 4:

                    echo '没有文件被上传';

                    break;

                case 6:

                    echo '找不到临时文件夹';

                    break;

                case 7:

                    echo '文件写入失败';

                    break;

            }

        }

    }
}