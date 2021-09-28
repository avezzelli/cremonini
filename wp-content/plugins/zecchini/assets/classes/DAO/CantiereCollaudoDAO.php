<?php

namespace zecchini;

class CantiereCollaudoDAO extends ObjectDAO {
    function __construct() {
        parent::__construct(DBT_CANCOL);
    }
    
    public function getArray(CantiereCollaudo $obj){
        return array(
            DBT_IDCANTIERE      => $obj->getIdCantiere(),
            DBT_IDCOLLAUDO      => $obj->getIdCollaudo()
        );
    }
    
    public function getFormato(){
        return array('%d', '%d');
    }
    
    public function getObj($item){
        $obj = new CantiereCollaudo();
        $obj->setID($item[DBT_ID]);
        $obj->setIdCantiere($item[DBT_IDCANTIERE]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        return $obj;
    }
    
    public function save(CantiereCollaudo $obj){
        return parent::saveObject($this->getArray($obj), $this->getFormato());
    }
    
    public function getCantieriCollaudo($where = null){
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
        $obj = updateToCanCol($o);
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
