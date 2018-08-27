<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/12
 * Time: 下午11:40
 */
namespace Creater\App\Odp;

use Creater\App\CreateBase;
use Creater\Helper\CommonHelper;
use Creater\Helper\FileHelper;
use Creater\Helper\TemplateHelper;

class CreateAll
{
    private $_ActionParams;
    private $_ControllerParams;
    private $_DaoParams;
    private $_DsParams;
    private $_PsParams;
    private $_Config;

    public function __construct($params)
    {
        $this->_ActionParams     = $params;
        $this->_ControllerParams = $params;
        $this->_DaoParams        = $params;
        $this->_DsParams         = $params;
        $this->_PsParams         = $params;
        $this->_Config           = $GLOBALS['config']['ODP']['ALL'];
    }

    /**
     * 创建
     */
    public function create()
    {
        $this->_DaoParams['path']    = $this->_Config['DOCUMENT_PATH']['DAO'];
        $this->_DaoParams['db_name'] =  $GLOBALS['config']['ODP']['DB']['PREFIX'] . ucfirst($this->_DaoParams['base_name']);
        $this->_DaoParams['base_name'] = 'Dao_' . $this->_DaoParams['base_name'];
        (new CreateDao($this->_DaoParams))->create();

        $this->_ActionParams['path']        = $this->_Config['DOCUMENT_PATH']['ACTION'] . 'Action';
        $this->_ActionParams['base_config'] = ['-n',strtolower($this->_ActionParams['base_name'])];
        $this->_ActionParams['base_name']   = 'Action_'.$this->_ActionParams['base_name'];
        (new CreateAction($this->_ActionParams))->create();

        $this->_ControllerParams['path']      = $this->_Config['DOCUMENT_PATH']['CONTROLLER'] . 'Controller';
        $this->_ControllerParams['base_name'] = 'Controller_'.$this->_ControllerParams['base_name'];
        (new CreateController($this->_ControllerParams))->create();

        $this->_DsParams['path']  = $this->_Config['DOCUMENT_PATH']['DATASERVICE'];
        $this->_DsParams['base_name']  = 'Service_Data_' . $this->_DsParams['base_name'];
        (new CreateDataService($this->_DsParams))->create();

        $this->_PsParams['path']  = $this->_Config['DOCUMENT_PATH']['PAGESERVICE'];
        $this->_PsParams['base_name']  = 'Service_Page_' . $this->_PsParams['base_name'];
        (new CreatePageService($this->_PsParams))->create();

        echo 'CREATE SUCCESS !' . PHP_EOL;
    }

}