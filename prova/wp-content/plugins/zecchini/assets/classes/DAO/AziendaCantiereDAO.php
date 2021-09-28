<?php

namespace zecchini;

class AziendaCantiereDAO extends ObjectDAO {
    function __construct() {
        parent::__construct(DBT_AZICAN);
    }
    
    public function getArray(AziendaCantiere $obj){
        return array(
            DBT_IDCANTIERE  => $obj->getIdCantiere(),
            DBT_IDAZIENDA   => $obj->getIdAzienda()
        );
    }
    
    public function getFormato(){
        return array('%d', '%d');
    }
    
    public function getObj($item){
        $obj = new AziendaCantiere();
        $obj->setID($item[DBT_ID]);
        $obj->setIdCantiere($item[DBT_IDCANTIERE]);
        $obj->setIdAzienda($item[DBT_IDAZIENDA]);
        return $obj;
    }
    
    public function save(AziendaCantiere $obj){
        return parent::saveObject($this->getArray($obj), $this->getFormato());
    }
    
    public function getAziendeCantieri($where = null){
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
        $obj = updateToAziCan($o);
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
