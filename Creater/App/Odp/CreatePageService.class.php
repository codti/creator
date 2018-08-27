<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/12
 * Time: 下午11:41
 */
namespace Creater\App\Odp;

use Creater\App\CreateBase;
use Creater\Helper\FileHelper;
use Creater\Helper\TemplateHelper;

class CreatePageService extends CreateBase
{
    public function __construct($params)
    {
        parent::__construct($params);
        $this->_Config = $GLOBALS['config']['ODP']['DATASERVICE'];

    }

    public function create()
    {
        //拼装数组
        $map = [
            'CLASS_NAME'   => $this->params['base_name'],
            'PARENT_CLASS' => !empty($this->_Config['PARENT_CLASS']) ? 'extends ' . $this->_Config['PARENT_CLASS'] : '',
        ];

        $map = array_merge($map,$this->note);

        $tmpl = TemplateHelper::fetchTemplate('pageservice');
        $this->content = TemplateHelper::parseTemplateTags($map,$tmpl);
        FileHelper::writeToFile($this->content,$this->params['path'],$this->params['file_name']);

        echo 'CREATE SUCCESS !' . PHP_EOL;
    }

}




