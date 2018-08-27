<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/12
 * Time: 下午11:41
 */
namespace Creater\App\Odp;

use Creater\App\CreateBase;
use Creater\App\TableCreate;
use Creater\Helper\CommonHelper;
use Creater\Helper\FileHelper;
use Creater\Helper\TemplateHelper;

class CreateDataService extends CreateBase
{
    use TableCreate;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_Config = $GLOBALS['config']['ODP']['DATASERVICE'];

    }

    public function create()
    {
        //初始化
        $this->DBConstruct();
        //设置表名
        $this->setTableName($this->params['db_name']);
        //获取数据
        $columnList = $this->getColumnList();

        $allFields  = array();
        foreach ($columnList as $column) {
            $allFields[] = CommonHelper::convertUnderline($column['COLUMN_NAME'],false);
        }
        $allFields = implode(',',$allFields);
        //拼装数组
        $map = [
            'CLASS_NAME'   => $this->params['base_name'],
            'PARENT_CLASS' => !empty($this->_Config['PARENT_CLASS']) ? 'extends ' . $this->_Config['PARENT_CLASS'] : '',
            'ALL_FIELDS'   => $allFields,
        ];

        $map = array_merge($map,$this->note);

        $tmpl = TemplateHelper::fetchTemplate('dataservice');
        $this->content = TemplateHelper::parseTemplateTags($map,$tmpl);
        FileHelper::writeToFile($this->content,$this->params['path'],$this->params['file_name'],$this->_Config['FILE_NAME_TEMP']);

        echo 'CREATE SUCCESS !' . PHP_EOL;
    }

}




