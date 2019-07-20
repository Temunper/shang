<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\admin\controller;


use app\admin\common\Upload;
use app\admin\model\ClasModel;
use Overtrue\Pinyin\Pinyin;
use think\Controller;
use think\Request;
use think\Validate;

class Clas extends Controller
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
        $this->assign('class',$this->get_all_clas());
        return $this->fetch();
    }

//    得到所有的分类
    public function get_all_clas()
    {
        $result = $this->clas_model->get_all_clas();
        $result = $this->set_tree($result);
        return $result;

    }

//    查询分类
    public function get_class_by_id($class_id)
    {
        $result = $this->clas_model->get_class_by_id($class_id);
        if (empty($result['f_class_id'])) {
            $result = ['f_class_id' => $result['class_id'],
                'f_class_name' => $result['class_name'],
                'class_id' => '',
                'class_name' => ''
            ];
        }
        return $result;
    }

//    将数组变为生成树,仅限两层
    public function set_tree($data)
    {
        foreach ($data as $all_list) {
            $temp[$all_list['class_id']] = $all_list;     //以id为索引
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
        return $tree;
    }

//    添加分类
    public function add(Request $request)
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
        $d_clas = $clas->where('name', '=', $request->param('name'))->where("status = 1")->select();
        if ($d_clas) {
            $data = ['code' => 202, 'data' => '分类已存在'];
            return json_encode($data);
        }
        $clas->status = 1;
        $clas->sort = $request->param('sort');
        $clas->name = $request->param('name');
        $clas->describe = $request->param('describe');
        $clas->image = $file_path;
        $clas->pinyin = implode('', $pinyin->convert($request->param('name')));
        $validate = $this->validate($clas, 'Clas');
        if ($validate && $clas->save()) {
            $data = ['code' => 200, 'data' => "添加成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "添加失败" . $validate];
            return json_encode($data);
        }

    }

//    删除分类
    public function del_clas()
    {
        $params = Request::instance()->post();
        $d_clas = $this->get_all_clas();
        $class_id = [];
        $class_id[] = (int)$params['class_id'];
        foreach ($d_clas as $value) {
            if ($value['class_id'] == $params['class_id'] && $value['son'] != null) {
                foreach ($value['son'] as $item) {
                    $class_id[] = $item['class_id'];
                }
            }
        }
        $result = $this->clas_model->del_clas($class_id, 2);
        if ($result) {
            $data = ['code' => 200, 'msg' => "删除成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'msg' => "删除失败"];
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
        if ($d_clas && $d_clas->name != $request->param('name')) {
            $data = ['code' => 202, 'data' => '分类已存在'];
            return json_encode($data);
        }
        $clas = ClasModel::get($request->param('class_id'));
        $clas->sort = $request->param('sort');
        $clas->name = $request->param('name');
        $clas->describe = $request->param('describe');
        if ($file_path) $clas->image = $file_path;
        $clas->pinyin = implode('', $pinyin->convert($request->param('name')));
        $validate = $this->validate($clas->toArray(), 'Clas');
        dump($validate);
        if ($validate && $clas->isUpdate(true)->save()) {
            $data = ['code' => 200, 'data' => "修改成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "修改失败" . $validate];
            return json_encode($data);
        }

    }
}