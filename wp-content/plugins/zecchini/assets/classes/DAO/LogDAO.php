<?php

namespace zecchini;

class LogDAO extends ObjectDAO implements InterfaceDAO{
    
    function __construct() {
        parent::__construct(DBT_LOG);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }
    
    public function deleteObject($array): bool {
        return parent::deleteObject($array);
    }

    public function exists(MyObject $o) {
        
    }

    public function getArray(MyObject $o) {
        $obj = updateToLog($o);
        //imposto il timezone
        date_default_timezone_set('Europe/Rome');
        $timestamp = date('Y-m-d H:i:s', strtotime("now"));
        
        return array(
            DBT_IDGV        => $obj->getIdGV(),
            DBT_IDVOCE      => $obj->getIdVoce(),
            DBT_STATO       => $obj->getStato(),
            DBT_UC_UW       => $obj->getUtenteWP(),
            DBT_LOG_DATA    => $timestamp,
            DBT_IDCOLLAUDO  => $obj->getIdCollaudo()
        );
    }

    public function getArrayResult($resultQuery) {
        if(checkResult($resultQuery)){
            $result = array();
            foreach($resultQuery as $item){
                array_push($result, $this->getObj($item));
            }
            return $result;
        }
        return null;
    }

    public function getFormato() {
        return array('%d', '%d', '%d', '%d', '%s', '%d');
    }

    public function getObj($item) {
        $obj = new Log();
        $obj->setID($item[DBT_ID]);
        $obj->setIdGV($item[DBT_IDGV]);
        $obj->setIdVoce($item[DBT_IDVOCE]);
        $obj->setStato($item[DBT_STATO]);
        $obj->setUtenteWP($item[DBT_UC_UW]);
        $obj->setDataOperazione($item[DBT_LOG_DATA]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        return $obj;
    }

    public function getResults($where = null, $order = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $order, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToLog($o);
        $campi = $this->getArray($obj);
        $formato = $this->getFormato();
        return parent::saveObject($campi, $formato);
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToLog($o);
        $update = $this->getArray($obj);
        $formatUpdate = $this->getFormato();
        $where = array(DBT_ID => $obj->getID());
        $formatWhere = array('%d');
        return parent::updateObject($update, $formatUpdate, $where, $formatWhere);
    }
    
    public function getResultByID($ID) {
        $temp = parent::getResultByID($ID);
        if($temp != null){
            return $this->getObj($temp);
        }
        return null;
    }

}
