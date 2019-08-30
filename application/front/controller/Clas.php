<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\front\controller;


use app\admin\common\Upload;
use app\admin\model\ClasModel;
use Overtrue\Pinyin\Pinyin;
use think\Controller;
use think\Request;

class Clas extends Base
{
    protected $clas_model = null;

    public function __construct(Request $request = null)
    {
        $this->clas_model = new ClasModel();
        parent::__construct($request);
    }
//    查询所有分类

    /**
     * @return null
     */

//    clas页
    public function clas()
    {
        $this->assign('clas', $this->get_all_clas());
        return $this->fetch();
    }

//    add页
    public function plus()
    {
        $this->assign('clas', $this->get_all_clas());
        return $this->fetch("clas/add");
    }

//    content页
    public function content()
    {
        $request = Request::instance()->param();
        $clas = ClasModel::get($request['class_id'], function ($query) {    //根据id获取分类的详细数据
            $query->where("status", 1);
        });
        $this->assign('clas', $this->get_all_clas());
        $this->assign('d_clas', $clas->toArray());
        return $this->fetch();
    }

//    得到所有的分类
    public function get_all_clas()
    {
        $clas = $this->clas_model->get_all_clas();
        if ($clas){
            $result = $this->set_tree($clas);                 //将二级分类生成树型结构
            return $result;
        }
        else
            return null;
    }

    public function get_one_clas($project_id)
    {
        $result = $this->clas_model->get_one_clas($project_id);
       // $result = $this->set_tree($result);                 //将二级分类生成树型结构
        return $result;
    }

//    查询分类
    public function get_class_by_id($class_id)
    {
        $result = $this->clas_model->get_class_by_id($class_id);   //根据id查询分类
        if (empty($result['f_class_id'])) {                         //没有父级id，说明自己就是一级分类
            $result = ['f_class_id' => $result['class_id'],
                'f_class_name' => $result['class_name'],
//                'f_keywords'=>$result['f_keywords'],
//                'f_description'=>$result['f_describe'],
//                'keywords'=>'',
//                'description'=>'',
                'class_id' => '',
                'class_name' => ''
            ];
        }
        return $result;                                                //返回数据
    }

//    将数组变为生成树
    public function set_tree($data)
    {
        foreach ($data as $all_list) {
            $temp[$all_list['class_id']] = $all_list;     //以class_id为索引
        }
        foreach ($temp as $all_list) {
            $pid = $all_list['f_class_id'];
            $son_id = $all_list['class_id'];
            if (isset($temp[$pid])) {
                $temp[$pid]['son'][] = &$temp[$son_id];      //前后共用一个地址。即将加入到当前父级的二级数组中
            } else {
                $temp[$son_id]['son'] = null;
                $tree[] = &$temp[$son_id];               //数组引用没有父级的条目的地址。
            }
        }
        array_multisort(array_column($tree, 'sort'), SORT_ASC, $tree);
        foreach ($tree as $key => $item) {
            if ($item['son'] != null) {
                array_multisort(array_column($item['son'], 'sort'), SORT_ASC, $item['son']);
                unset($tree[$key]['son']);
                $tree[$key]['son'] = $item['son'];
            }
        }
        return $tree;
    }

//    添加分类
    public function add(Request $request)
    {
        if ($request->param('f_class_id') != 0) {            //判断一级分类是否存在
            $result = $this->clas_model->get_class_by_id($request->param('f_class_id'));
            if (!$result) {
                $data = ['code' => 202, 'data' => "一级分类不存在"];
                return json_encode($data);
            }
        }
        $file = $request->file('file');                        //将上传文件保存，获得地址，若是cdn则直接存地址
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'class');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $pinyin = new pinyin();                                                     //实例化拼音对象
        $clas = new ClasModel();
        $d_clas = $clas->where('name', '=', $request->param('name'))->where("status = 1")->select();
        if ($d_clas) {                                                                  //判断分类是否重复
            $data = ['code' => 202, 'data' => '分类已存在'];
            return json_encode($data);
        }
        $clas->status = 1;
        $clas->f_class_id = $request->param('f_class_id');
        $clas->sort = $request->param('sort');
        $clas->name = $request->param('name');
        $clas->describe = $request->param('describe');
        $clas->image = $file_path;
        $clas->pinyin = implode('', $pinyin->convert($request->param('name'), PINYIN_KEEP_ENGLISH));       //生成拼音
        $validate = $this->validate($clas->toArray(), 'Clas');                              //验证分类信息
        if ($validate === true && $clas->save()) {                                                  //根据验证结果存储数据并返回结果
            $data = ['code' => 200, 'data' => "添加成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "添加失败" . $validate];
            return json_encode($data);
        }

    }

//    删除分类
    public function delete()
    {
        $params = Request::instance()->post();
        $d_clas = $this->get_all_clas();                    //获得所有分类的树型结构
        $class_id = [];
        $class_id[] = (int)$params['class_id'];
        foreach ($d_clas as $value) {                        //循环遍历获取分类的树叶
            if ($value['class_id'] == $params['class_id'] && $value['son'] != null) {
                foreach ($value['son'] as $item) {
                    $class_id[] = $item['class_id'];
                }
            }
        }
        $result = $this->clas_model->del_clas($class_id, 2);  //将获得分类id的和其树叶置为2删除
        if ($result) {
            $data = ['code' => 200, 'data' => "删除成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "删除失败"];
            return json_encode($data);
        }
    }


//    修改分类
    public function update(Request $request)
    {
        if ($request->param('f_class_id') != 0) {            //判断一级分类
            $result = $this->clas_model->get_class_by_id($request->param('f_class_id'));
            if (!$result) {
                $data = ['code' => 202, 'data' => "一级分类不存在"];
                return json_encode($data);
            }
        }
        $file = $request->file('file');                        //将上传文件保存，获得地址，若是cdn则直接存地址
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'class');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $pinyin = new pinyin();
        $clas = new ClasModel();
        $d_clas = $clas->where('name', '=', $request->param('name'))->where("status = 1")->find();
        if ($d_clas && $d_clas->name != $request->param('name')) {                                             //判断分类是否重复
            $data = ['code' => 202, 'data' => '分类已存在'];
            return json_encode($data);
        }
        $clas = ClasModel::get($request->param('class_id'));
        $clas->sort = $request->param('sort');
        $clas->name = $request->param('name');
        $clas->describe = $request->param('describe');
        if ($file_path) $clas->image = $file_path;
        $pinyin = implode('', $pinyin->convert($request->param('name'), PINYIN_KEEP_ENGLISH));   //生成拼音
        !$pinyin ? $clas->pinyin = $request->param('name') : $clas->pinyin = $pinyin;
        $validate = $this->validate($clas->toArray(), 'Clas');                    //验证分类信息
        if ($validate && $clas->isUpdate(true)->save()) {                        //根据验证结果存储数据并返回结果
            $data = ['code' => 200, 'data' => "修改成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "修改失败" . $validate];
            return json_encode($data);
        }

    }
}