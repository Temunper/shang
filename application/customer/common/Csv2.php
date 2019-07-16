<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/12
 * Time: 14:34
 */

namespace app\customer\common;
class Csv2
{
    /**
     * 导出csv文件
     * @param $list 数据源
     * @param $title 数据列表
     */
    public function put_csv($list,$title)
    {
        $file_name = "留言".time().".csv";//文件名
        header('Content-Type: application/vnd.ms-excel');//设置内容类型为Excel
        header('Content-Disposition: attachment;filename='.$file_name );//下载文件
        header('Cache-Control: max-age=0');//表示当访问此网页后的0秒内再次访问不会去服务器
        $file = fopen('php://output',"a");//打开文件或者 URL,  php://output 是一个只写的数据流， 允许你以 print 和 echo 一样的方式 写入到输出缓冲区,  a:写入方式打开，将文件指针指向文件末尾。如果文件不存在则尝试创建之。
        $limit = 1000;
        $calc = 0;
        foreach ($title as $v){
            $tit[] = iconv('UTF-8', 'GB2312//IGNORE',$v);//转码
        }
        fputcsv($file,$tit);//将行格式化为 CSV 并写入一个打开的文件中。(标题)
        foreach ($list as $v){
            $calc++;
            //-------核心！！！清空缓存，将缓存上的数据写入到文件--------
            if($limit == $calc){
                ob_flush();//将本来存在输出缓存中的内容取出来，调用ob_flush()之后缓冲区内容将被丢弃。
                flush();   //待输出的内容立即发送。   具体查看：https://www.jb51.net/article/37822.htm
                $calc = 0;
            }//-------核心--------
            foreach($v as $t){
                $tarr[] = iconv('UTF-8', 'GB2312//IGNORE',$t);
            }
            fputcsv($file,$tarr);//将行格式化为 CSV 并写入一个打开的文件中。（内容）
            unset($tarr);//销毁指定的变量
        }
        unset($list);//销毁指定的变量
        fclose($file);//关闭打开的文件
        exit();
    }

    // csv导入,此格式每次最多可以处理1000条数据（我觉得这个是不对的，他规定的是读取一行的最大长度）
    //$filename  文件路径
    public function input_csv($filename) {
        $csv_file = $handle = fopen($filename,'r');//只读方式打开，将文件指针指向文件头]
        $result_arr = array ();
        $i = 0;
        //函数从文件指针中读入一行并解析 CSV 字段（一维数组）
        while($data_line = fgetcsv($csv_file,1000)) {
            //跳过第一行标题读取
            if ($i == 0) {
                $GLOBALS ['csv_key_name_arr'] = $data_line;//将标题存储起来
                $i ++;
                continue;
            }
            //读取内容
            foreach($GLOBALS['csv_key_name_arr'] as $csv_key_num => $csv_key_name ) {
                $csv_key_name = iconv('gb2312','utf-8', $csv_key_name);//标题
                if(empty($data_line[$csv_key_num])) {
                    $result_arr[$i][$csv_key_name] = '';
                }else {
                    $value = iconv('gb2312','utf-8', $data_line[$csv_key_num]);//标题对应的内容
                    $result_arr[$i][$csv_key_name] = $value;
                }
            }
            $i++;
        }
        fclose($handle); // 关闭指针
        return $result_arr;
    }
}