<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/11
 * Time: 下午10:40
 */
namespace Creater\Helper;
class TemplateHelper
{
    /**
     * 获取模板文件中的内容
     * @param string $template_name
     * @return string
     */
    public static function fetchTemplate($template_name)
    {
        if (self::hasTemplate($template_name)) {
            $file = $GLOBALS['config']['ODP']['TEMPLATES'][$template_name];
            $content = file_get_contents(TMPL_PATH . $file);
        }else{
            echo 'TEMPLATE NOT EXISTS'.PHP_EOL;
            exit();
        }
        return $content;
    }

    /**
     * 判断模板是否存在
     * @param string $template_name
     * @return bool
     */
    public static function hasTemplate($template_name)
    {
        return key_exists($template_name, $GLOBALS['config'][FRAME]['TEMPLATES']);
    }

    /**
     * 通过数组来修改模板
     * @param array $map
     * @param $template
     * @return mixed
     */
    public static function parseTemplateTags($map = [], $template)
    {
        $_c = $template;
        foreach ($map as $key => $item) {
            $_c = str_replace('{{' . $key . '}}', $item, $_c);
        }
        return $_c;
    }
}









