<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/12
 * Time: 下午11:40
 */
namespace Creater\App\Odp;

use Creater\App\CreateBase;
use Creater\Helper\FileHelper;
use Creater\Helper\TemplateHelper;

class CreateAction extends CreateBase
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->_Config = $GLOBALS['config']['ODP']['ACTION'];
    }

    /**
     * 创建
     */
    public function create()
    {
        //拼装数组

        if (in_array($this->_Config['BASE_CONFIG']['MIDDLE_NAME'],$this->params['base_config'])) {
            $key = array_search($this->_Config['BASE_CONFIG']['MIDDLE_NAME'],$this->params['base_config']) + 1;
            $this->params['path'] .= DS .$this->params['base_config'][$key];
        }

        $map = [
            'CLASS_NAME'   => $this->params['base_name'],
            'PARENT_CLASS' => !empty($this->_Config['PARENT_CLASS']) ? 'extends ' . $this->_Config['PARENT_CLASS'] : '',
        ];

        $map = array_merge($map,$this->note);

        //获取模板
        $tmpl = TemplateHelper::fetchTemplate('action');
        //填充模板
        $this->content = TemplateHelper::parseTemplateTags($map,$tmpl);
        $path = str_ireplace('action','actions',$this->params['path']);
        FileHelper::writeToFile($this->content,$path,$this->params['file_name']);

        echo 'CREATE SUCCESS !' . PHP_EOL;
    }

}