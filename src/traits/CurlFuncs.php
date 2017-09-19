<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 17:01
 */

namespace Chenzeshu\ChenUtils\Traits;


trait CurlFuncs
{
    /**
     * 生成长度为$length的随机字符串
     * @param $length
     * @return string
     */
    protected function createRandChar($length){
        $varpol = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($varpol) -1; //神坑
        $randChar = '';
        for($i = 0; $i < $length; $i++){
            $randChar .= $varpol[rand(0, $max)];
        }
        return $randChar;
    }

    /**
     * get方法访问
     * @param $url  要求带参数的完整url
     * @return mixed
     */
    protected function curl_get($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMECONDITION, 10);

        $contents = curl_exec($ch);

        curl_close($ch);

        return $contents;
    }
}