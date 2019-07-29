<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['user/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['user/hello', ['method' => 'post']],
    ],
    '/$' => 'front/index/index',
    'front$'=>'front/index/index',
    'slist/[:class_id]/[:area]/[:money]/[:pro_name]$'=>'front/slist/slist',
    'newslist/[:type]$'=>'front/article/newslist',
    'article/[:article_id]$'=>'front/detail/detail',
    'project/[:project_id]$'=>'front/project/project',
];
//class_id=1002&area=&money=