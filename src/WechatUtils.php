<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 17:00
 */

namespace Chenzeshu\ChenUtils;

use Chenzeshu\ChenUtils\Others\Scope;
use Chenzeshu\ChenUtils\Exceptions\TokenExp\TokenCachedExcepion;
use Chenzeshu\ChenUtils\Exceptions\TokenExp\WxCurlException;
use Chenzeshu\ChenUtils\Traits\CurlFuncs;
use Illuminate\Support\Facades\Cache;

class WechatUtils
{
    use CurlFuncs;

    protected $app_id;
    protected $app_secret;
    protected $login_url;

    function __construct()
    {
        $this->app_id = config('chen.app_id');
        $this->app_secret = config('chen.app_secret');
        $this->login_url = config('chen.login_url');
    }

    public function iwant()
    {
        return "我的包";
    }

    /**
     * 根据$code得到access_token
     * @param $code 微信$code
     */
    public function get($code)
    {
        $login_url = sprintf($this->login_url, $this->app_id, $this->app_secret, $code);
        $wx_string = $this->curl_get($login_url);
        $wx_arr = json_decode($wx_string, true);
        if(!$wx_arr){
            throw new WxCurlException();
        }
        else{
            $loginFail = array_key_exists('errcode', $wx_arr);
            if($loginFail){
                return "失败啦!";
            }
            else{
                return $this->grantToken($wx_arr);
            }
        }
    }

    /**
     * 根据$code得到$openid及session_key
     * @param $wx_arr
     * @return array
     */
    public function getOpenid($code){
        $login_url = sprintf($this->login_url, $this->app_id, $this->app_secret, $code);
        $wx_string = $this->curl_get($login_url);
        $wx_arr = json_decode($wx_string, true);
        if(!$wx_arr){
            throw new WxCurlException();
        }
        else {
            $loginFail = array_key_exists('errcode', $wx_arr);
            if ($loginFail) {
                return "失败啦!";
            } else {
                return $wx_arr;
            }
        }
    }

    private function grantToken($wx_arr)
    {
        $openid = $wx_arr['openid'];
        $user = config("chen.User")::firstOrCreate([
            'openid' => $openid
        ]);
        $wx_arr = $this->prepareCachedValue($wx_arr, $user->id);
        $re = $this->saveToCache($wx_arr);
        if($re){
            return [
                'token' => $re,
            ];
        }
        else{
            throw new TokenCachedExcepion();
        }
    }

    /**
     * 为$wx_arr添加scope及uid值,uid就是user在user表的id
     * @param $wx_arr 通过code接口请求到的session_key,openid等
     */
    private function prepareCachedValue($wx_arr, $uid)
    {
        $wx_arr['uid'] = $uid;
        $wx_arr['scope'] = Scope::User;

        return $wx_arr;
    }

    /**
     * 存入缓存，并定义键值为token
     */
    public function saveToCache($wx_arr)
    {
        $token = $this->generateToken();
        $expire_in = config('chen.token_expire_in');
        //fixme 请在laravel自带的Cache类的put和putmany方法中带上`return true`

        $re = Cache::put($token, $wx_arr, $expire_in);
        if($re){
            return $token;
        }else{
            return false;
        }
    }

    /**
     * 生成token(需要对32位字符串混合客户端请求时间戳和盐进行md5加密)
     */
    private function generateToken()
    {
        $randChars = $this->createRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('chen.token_salt');

        $token = md5($randChars.$timestamp.$salt);
        return $token;
    }

    /**
     * @param $token
     * @param $key
     * @return mixed
     */
    public static function getCurrentTokenVar($request, $key = '')
    {
        $token = $request->header('token');
        $value = Cache::get($token);
        if($key){
            return $value[$key];
        }
        return $value;
    }

    /**
     * 为了更简单地获取uid，对getCurrentTokenVar进行了再封装
     * @return mixed
     */
    public static function getCurrentUid($request)
    {
        return self::getCurrentTokenVar($request, 'uid');
    }

    public static function getCurrentOpenid($request)
    {
        return self::getCurrentTokenVar($request, 'openid');
    }

    public function test()
    {
        dd('i am chenutils');
    }
}