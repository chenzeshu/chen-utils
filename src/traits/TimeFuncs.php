<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 16:33
 */

namespace Chenzeshu\ChenUtils\Traits;


trait TimeFuncs
{
    /**
     * 日期格式转换
     * @param $time
     * @return false|string
     */
    protected function toTime($time){
        return date('Y-m-d', strtotime($time));
    }
}