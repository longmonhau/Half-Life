<?php namespace lOngmon\Hau\core\component;
/**
 * 字符串处理组件
 * @package Component
 * @author lOngmon Hau <longmon.hau@gmail.com>
 */
class tString {
    const ALPHA = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    const NUMBERIC = "1234567890";
    const SYMBOL = "~!@#$%^&*()_+{}[]=-:;?.>,<";
    
    /**
     * 生产随机字符串
     * @param $len int 要生产随机字符串的长度， 默认为6位
     * @param $chars string 随机源字符
     * @return string 返回随机字符串
     */
    public static function rand( $len = 6, $chars = '' ) {
        if ( $chars == '' ) {
            $chars = self::ALPHA.self::NUMBERIC.self::SYMBOL;
        }
        $strlen = strlen( $chars );
        $randStr = '';
        for ($i=0; $i<$len; $i++) {
            $n = mt_rand(0,$strlen-1);
            $randStr .= $chars[$n];
        }
        return $randStr;
    }
}