<?php
/**
 * @name    Service_Data_Model
 * @desc    model data service, 按主题组织数据, 提供细粒度数据接口
 * @author  zhengzhiqing@zuoyebang.com
 */
class Service_Data_Model {
    private $_objDaoModel;
    private $_arrOutPut;

    public function __construct(){
        $this->_objDaoModel     = new Dao_Model();
        $this->_arrOutPut       = array();
    }
    const ALL_FIELDS = 'menuSort,menuTitle,menuUrl,menuType,menuPrivileges,sys_type';
    /**
     * 新增Model
     *
     * @param  array  $arrParams banner属性
     * @return bool true/false
     */
    public function addModel($arrParams) {
        if(strlen(strval($arrParams['menuTitle'])) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[param: ". $arrParams['title']  . "]");
            return false;
        }

        $arrFields = array(
            'menuSort'     => intval($arrParams['menuSort']),
            'menuTitle'    => isset($arrParams['menuTitle']) ? strval($arrParams['menuTitle']) : '',
            'menuUrl'      => isset($arrParams['menuUrl']) ? strval($arrParams['menuUrl']) : '',
            'menuType'    => isset($arrParams['menuType']) ? strval($arrParams['menuType']) : 0,
            'menuPrivileges' => isset($arrParams['menuPrivileges']) ? json_encode($arrParams['menuPrivileges']) : '',
            'sys_type'     => intval($arrParams['sys_type']),
        );
        $ret = $this->_objDaoModel->insertRecords($arrFields);

        return $ret;
    }

    /**
     * 更新model
     *
     * @param  int  $modelId  课程id
     * @param  array  $arrParams 课程属性
     * @return bool true/false
     */
    public function updateModel($modelId, $arrParams) {
        if(intval($modelId) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[modelId:$modelId]");
            return false;
        }

        $arrConds = array(
            'modelId' => intval($modelId),
        );

        $arrFields = array();
        $arrAllFields = explode(',', self::ALL_FIELDS);
        foreach($arrParams as $key => $value) {
            if(!in_array($key, $arrAllFields)) {
                continue;
            }

            $arrFields[$key] = $value;
        }
        if(isset($arrFields['menuPrivileges'])){
            $arrFields['menuPrivileges'] = json_encode($arrFields['menuPrivileges']);
        }

        $ret = $this->_objDaoModel->updateByConds($arrConds, $arrFields);

        return $ret;
    }
    /**
     * 删除model
     *
     * @param  int  $modelId  课程id
     * @return bool true/false
     */   
    public function delModel($modelId){
        if(intval($modelId) <= 0){
            Bd_Log::warning("Error:[param error], Detail:[modelId:$modelId]");
            return false;     
        }
        $arrConds = array();
        $arrConds['modelId'] = $modelId;
        return $this->_objDaoModel->updateByConds($arrConds, array("deleted" => 1));
    }

}
