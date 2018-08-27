<?php
/**
 * file  : CommonHelper.class.php
 * author: chenzhiwen@zuoyebang.com
 * date  : 2018/7/16
 * brief :
 */
namespace Creater\Helper;
class CommonHelper
{
    /**
     * 下划线命名转驼峰式命名
     * @param $str
     * @param bool $ucfirst true 大驼峰 false 小驼峰
     * @param bool $underline true 下划线保留 false 下划线舍弃
     * @return string
     */
    public static function convertUnderline($str,$ucfirst = true,$underline = false)
    {
        while(($pos = strpos($str , '_'))!==false) {
            $str = $underline ? substr($str , 0 , $pos) . '-' . ucfirst(substr($str , $pos+1)) : substr($str , 0 , $pos) . ucfirst(substr($str , $pos+1));
        }
        $str = str_replace('-','_',$str);
        return $ucfirst ? ucfirst($str) : $str;
    }

    /**
     * 二维数组转换为换行字符串, = 自动对齐
     * @param $arr
     * @param bool $akey
     * @return string
     */
    public static function array2strFormat($arr,$akey = false)
    {
        $str = '';
        $maxNum = 0;
        foreach ($arr as $arr2) {
            foreach ($arr2 as $key => $item) {
                $num = strlen($key) + 4;
                $maxNum = $maxNum < $num ? $num : $maxNum;
            }
        }

        foreach ($arr as $arr2) {
            foreach ($arr2 as $key => $item) {
                $num  = strlen($key);
                $s    = str_pad('=>',$maxNum - $num,' ',STR_PAD_LEFT)." ";
                $_s   = '\''.$key .'\''. $s . '\''.$item .'\','.PHP_EOL;
                if ($akey) $_s = '\''.$key .'\''. $s .$item .','.PHP_EOL;
                $n    = strlen($_s);
                $str .= str_pad($_s,$maxNum + $n,' ',STR_PAD_LEFT);
            }
        }
        return rtrim($str);
    }

}