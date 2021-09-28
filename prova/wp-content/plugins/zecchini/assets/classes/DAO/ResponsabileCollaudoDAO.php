<?php

namespace zecchini;

class ResponsabileCollaudoDAO extends ObjectDAO {
    
    function __construct() {
        parent::__construct(DBT_RESPCOLLAUDO);
    }
    
    public function getArray(ResponsabileCollaudo $obj){
        return array(
            DBT_IDCOLLAUDO  => $obj->getIdCollaudo(),
            DBT_IDUTENTEC   => $obj->getIdUtenteC()
        );
    }
    
    public function getFormato(){
        return array('%d', '%d');
    }
    
    public function getObj($item){
        $obj = new ResponsabileCollaudo();
        $obj->setID($item[DBT_ID]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        $obj->setIdUtenteC($item[DBT_IDUTENTEC]);
        return $obj;        
    }
    
    public function save(ResponsabileCollaudo $obj){
        return parent::saveObject($this->getArray($obj), $this->getFormato());
    }
    
    public function getResponsabiliCollaudo($where = null){
        $result = array();
        $temp = parent::getObjectsDAO($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                array_push($result, $this->getObj($item));
            }
        }
        return $result;
    }
    
    public function deleteObject($array): bool {
        return parent::deleteObject($array);
    }
    
    public function deleteObjectByID($ID) {
        return parent::deleteObjectByID($ID);
    }
    
    public function update(MyObject $o){
        $obj = updateToRespCollaudo($o);
        return parent::updateObject($this->getArray($obj), $this->getFormato(), array(DBT_ID => $obj->getID()), array('%d'));
    }
    
    public function getResultByID($ID) {
        $temp = parent::getResultByID($ID);
        if($temp != null){
            return $this->getObj($temp);
        }
        return null;
    }
}

