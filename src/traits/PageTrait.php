<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 15:46
 */

namespace Chenzeshu\ChenUtils\Traits;


trait PageTrait
{
    public function getPaginator($page, $pageSize){
        $begin = ($page - 1) * $pageSize;

        $arr = explode('\\',get_class($this));
        $start = strpos($arr[count($arr)-1] ,'Controller' );
        $classname = substr_replace($arr[count($arr)-1], '', $start);
        //静态绑定
        //读取Models文件目录结构, 匹配$classname, 若匹配到, 取得匹配文件的namespace组装后进行静态绑定
        //todo if Laravel 使用Storage类
        return $this->matchFile($classname, $begin, $pageSize);
    }

    /**
     * @param $classname 我们想要找的文件(无.php, 要加)
     */
    public function matchFile($classname, $begin, $pageSize){
        $files = scandir(app_path('/Models'));
        //todo 只要是.php结尾的就是类文件, 否则是文件夹
        //所以第一遍循环.php文件, 是否匹配, 若无匹配, 再向下一层
        //todo first circle

        $flag = true;  //有没有必要向下一层
        $filename = $classname. '.php';
        $root = "App\\Models\\";
        //递归寻找匹配的.php文件
        foreach ($files as $file){
            if($file == $filename){
                $myModel =  $root . $classname;
                $flag = false;
            }
        }

        if($flag){
            //todo 向没有.php结尾的下一层进发
            foreach ($files as $file){
                if(!strpos($file, '.php')){
                    $_files = scandir(app_path('/Models/' .$file));
                    foreach($_files as $_file){
                        if($_file == $filename){
                            $myModel = "App\\Models\\" . $file."\\". $classname;
                        }
                    }
                }
            }
        }
        $data = (new $myModel)->offset($begin)->limit($pageSize)->get();
        return $data;
    }
}