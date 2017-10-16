<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 16:31
 */

namespace Chenzeshu\ChenUtils\Traits;


trait ArrayFuncs
{
    /**
     * 功能:多维数组求差集
     * 优化了in_array()
     * 原理是将多维数组递归转化为字符串后查找。
     * in_array is too slow when array is large
     */
    protected function arrayToString($arr){
        if (is_array($arr)){
            return implode(',', array_map('arrayToString', $arr));
        }
        return $arr;
    }

    protected function array_trim($arrs){
        foreach ($arrs as $k => $arr ){
            if(empty($arr)){
                unset($arrs[$k]);
            }
        }
        return $arrs;
    }

    /**
     * 求差集的主体函数
     * 查找多维数组$old的每一个最底元素是否存在于多维数组$new数组中, 最后返回不在$new数组中的元素的集合
     * @param $old 求差集的主体多维数组
     * @param $new 求差集的参照多维数组
     * @return mixed
     */
    protected function myGetMulDiff($old, $new){
        $new_str = $this->arrayToString($new);
        foreach ($old as $k=>$o){
            $old_str =  $this->arrayToString($o);
            $diff[$k] = false === strpos($new_str, $old_str) ? $o : [];
        }
        $diff = $this->array_trim($diff);
        return $diff;
    }

    /**
     * 在上述函数基础上改造得求并集
     * @param $old
     * @param $new
     * @return mixed $union 并集数组
     */
    protected function myGetMulUnion($old, $new){
        $new_str = $this->arrayToString($new);
        foreach ($old as $k=>$o){
            $old_str =  $this->arrayToString($o);
            $union[$k] = True === strpos($new_str, $old_str) ? $o : [];
        }
        $union = $this->array_trim($union);
        return $union;
    }
}