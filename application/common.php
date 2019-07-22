<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param array $data
 * @return array|false|string
 */
function json_encode_unicode($data = [])
{
    if (empty($data)) {
        return '';
    }
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}
//全局过滤参数
function special_filter($list){
    $result = [];
    foreach ($list as $key=>$item){
        $result[$key] =htmlspecialchars($item, ENT_QUOTES);
    }
    return $result;
}
