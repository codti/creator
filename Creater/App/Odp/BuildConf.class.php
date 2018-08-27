<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/8/14
 * Time: 下午11:13
 */
namespace Creater\App\Odp;

use Creater\Helper\FileHelper;
use Creater\Helper\TemplateHelper;

class BuildConf
{
    private $_Config;
    private $params;

    public function __construct($params)
    {
        $this->params  = $params;
        $this->_Config = $GLOBALS['config']['ODP']['CONF'];
    }

    /**
     * 创建
     */
    public function build()
    {
        $userNameParam = $this->_Config['BASE_CONFIG']['USER_NAME'];
        $pwdParam      = $this->_Config['BASE_CONFIG']['PWD'];
        $authorParam   = $this->_Config['BASE_CONFIG']['AUTHOR'];
        $appParam      = $this->_Config['BASE_CONFIG']['APP'];
        $baseConf      = $this->params['base_config'];

        $userName = $pwd = $author = $app = '';
        $flag = 0;
        if (in_array($userNameParam,$baseConf)) {
            $key = array_search($userNameParam,$baseConf) + 1;
            $userName = $baseConf[$key];
            $flag++;
        }
        if(in_array($pwdParam,$baseConf)){
            $key = array_search($pwdParam,$baseConf) + 1;
            $pwd = $baseConf[$key];
            $flag++;
        }
        if(in_array($authorParam,$baseConf)){
            $key = array_search($authorParam,$baseConf) + 1;
            $author = $baseConf[$key];
            $flag++;
        }
        if(in_array($appParam,$baseConf)){
            $key = array_search($appParam,$baseConf) + 1;
            $app = $baseConf[$key];
            $flag++;
        }
        if ($flag != 4){
            echo 'PARAMS ERROR !';
            exit;
        }

        $map = [
            'USER_NAME' => $userName,
            'PWD'       => $pwd,
            'AUTHOR'    => $author,
            'APP'       => $app,
        ];

        $path = ROOT_PATH . 'Creater' . DS . 'Conf' . DS . '';
        $fileName = 'Conf.php';

        $note = [
            'FILE'   => $fileName,
            'AUTHOR' => $author,
            'DATE'   => date('Y/m/d',time()),
        ];

        $map = array_merge($map,$note);

        //获取模板
        $tmpl = TemplateHelper::fetchTemplate('conf');
        //填充模板
        $content = TemplateHelper::parseTemplateTags($map,$tmpl);

        FileHelper::writeToFile($content,$path,$fileName);

        echo 'BUILD SUCCESS !' . PHP_EOL;

    }
}