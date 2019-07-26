<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/26
 * Time: 11:37
 */

namespace app\admin\common;


class CUrl
{
    public static function curl($request_url = '')
    {

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $request_url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4'));


        if (in_array('Content-Encoding: gzip', get_headers($request_url))) {
            //gzip
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
}