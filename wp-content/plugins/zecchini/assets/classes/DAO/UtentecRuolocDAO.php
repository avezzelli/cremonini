<?php

namespace zecchini;

class UtentecRuolocDAO extends ObjectDAO {
    function __construct() {
        parent::__construct(DBT_UTERUO);
    }
    
    public function getArray(UtentecRuoloc $obj){
        return array(
            DBT_IDUTENTEC   => $obj->getIdUtenteC(),
            DBT_IDRC        => $obj->getIdRuoloC(),
            DBT_IDCOLLAUDO  => $obj->getIdCollaudo(),
            DBT_IDAZIENDA   => $obj->getIdAzienda()
        );
    }
    
    public function getFormato(){
        return array('%d', '%d', '%d', '%d');
    }
    
    public function getObj($item){
        $obj = new UtentecRuoloc();
        $obj->setID($item[DBT_ID]);
        $obj->setIdUtenteC($item[DBT_IDUTENTEC]);
        $obj->setIdRuoloC($item[DBT_IDRC]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        $obj->setIdAzienda($item[DBT_IDAZIENDA]);
        return $obj;
    }
    
    public function save(UtentecRuoloc $obj){
        return parent::saveObject($this->getArray($obj), $this->getFormato());
    }
    
    public function getUtentiRuoli($where = null){        
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
        $obj = updateToUteRuo($o);
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
