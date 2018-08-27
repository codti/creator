<?php
/**
 * Class File 与文件相关的助手类
 */
namespace Creater\Helper;
class FileHelper
{
    /**
     * 判断目录是否存在，如果不存在则创建，可递归创建所有的父目录
     * @param $path
     */
    public static function mkdir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * 创建一组路径
     * @param array $paths
     */
    public static function mkdirs($paths = [])
    {
        foreach ($paths as $path) {
            FileHelper::mkdir($path);
        }
    }

    /**
     * 将内容写入到文件的方法
     * @param $content
     * @param $path
     * @param $fileName
     * @param $replace_str
     */
    public static function writeToFile($content,$path,$fileName,$replace_str = array())
    {
        foreach ($replace_str as $search => $replace) {
            $path    = str_replace($search,$replace,$path);
        }
        $_file =  $path . '/' . $fileName;
        if ($path !== '') {
            FileHelper::mkdir($path);
            file_put_contents($_file, $content);
        }
    }


    /**
     * 扫描某个路径下的所有文件，并拷贝到目标路径下，支持递归
     * @param $src_path
     * @param $tar_path
     */
    public static function copyFiles($src_path, $tar_path)
    {
        FileHelper::mkdir($tar_path);

        $files = scandir($src_path);
        foreach ($files as $file) {
            $src_file_name = $src_path . '/' . $file;
            $tar_file_name = $tar_path . '/' . $file;
            if (is_file($src_file_name)) {
                copy($src_file_name, $tar_file_name);
            }

            if (is_dir($src_file_name) && !preg_match('/\.+$/', $src_file_name)) {
                FileHelper::copyFiles($src_file_name, $tar_file_name);
            }
        }
    }
}