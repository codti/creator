<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/8/14
 * Time: 下午11:13
 */
namespace Creater\App\Odp;

use Creater\Helper\FileHelper;

class BuildApp
{
    private $_Config;
    private $params;

    public function __construct($params)
    {
        $this->params  = $params;
        $this->_Config = $GLOBALS['config']['ODP']['APP'];
    }

    /**
     * 创建
     */
    public function build()
    {
        //复制文件
        $baseName = strtolower($this->params['base_name']);

        $srcPath = TMPL_PATH . DS . 'base';
        $tarPath = $this->_Config['DOCUMENT_PATH'] . DS . $baseName;
        FileHelper::copyFiles($srcPath,$tarPath);

        $baseConfig = $this->params['base_config'];
        $namespaceParam = $this->_Config['BASE_CONFIG']['NAMESPACE'];

        if (in_array($namespaceParam,$baseConfig)) {
            $key = array_search($namespaceParam,$baseConfig) + 1;
            $namespace = $baseConfig[$key];
        }else{
            echo 'PARAMS ERROR !';
            exit;
        }

        $map = [
            'NAMESPACE'      => $namespace,
            'APP_NAME_CLASS' => $namespace,
            'APP_NAME'       => $baseName,
        ];

        $note = [
            'FILE'   => $this->params['file_name'],
            'AUTHOR' => $GLOBALS['config']['NOTE']['AUTHOR'],
            'DATE'   => date('Y/m/d',time()),
        ];

        $map = array_merge($map,$note);

        self::writeTmpl($tarPath,$map);

        $oldPath = $tarPath . DS . 'library' . DS . 'APP_NAME' . DS;
        $newPath = $tarPath . DS . 'library' . DS . $baseName . DS;

        rename($oldPath,$newPath);
        echo 'BUILD SUCCESS !' . PHP_EOL;

    }


    /**
     * 修改tmpl
     * @param $path
     * @param $map
     */
    private static function writeTmpl($path,$map)
    {
        $files = scandir($path);
        foreach ($files as $file) {
            $file_name = $path . '/' . $file;
            if (is_file($file_name) && preg_match('/\.tmpl$/', $file_name)) {
                $content = file_get_contents($file_name);
                foreach ($map as $key => $item) {
                    $content = str_replace('{{' . $key . '}}', $item, $content);
                }
                $_f = str_replace('.tmpl','.php',$file_name);
                file_put_contents($_f, $content);
                unlink($file_name);
            }

            if (is_file($file_name) && preg_match('/\.conf$/', $file_name)) {
                $content = file_get_contents($file_name);
                foreach ($map as $key => $item) {
                    $content = str_replace('{{' . $key . '}}', $item, $content);
                }
                file_put_contents($file_name, $content);
            }

            if (is_dir($file_name) && !preg_match('/\.+$/', $file_name)) {
                self::writeTmpl($file_name,$map);
            }
        }
    }

}