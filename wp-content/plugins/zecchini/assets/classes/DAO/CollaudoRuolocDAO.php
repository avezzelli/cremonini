<?php

namespace zecchini;

class CollaudoRuolocDAO extends ObjectDAO {
    function __construct() {
        parent::__construct(DBT_COLRUO);
    }
    
    public function getArray(CollaudoRuoloc $obj){
        return array(
            DBT_IDCOLLAUDO  => $obj->getIdCollaudo(),
            DBT_IDRC        => $obj->getIdRuoloC()
        );
    }
    
    public function getFormato(){
        return array('%d', '%d');
    }
    
    public function getObj($item){
        $obj = new CollaudoRuoloc();
        $obj->setID($item[DBT_ID]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        $obj->setIdRuoloC($item[DBT_IDRC]);
        return $obj;
    }
    
    public function save(CollaudoRuoloc $obj){
        return parent::saveObject($this->getArray($obj), $this->getFormato());
    }
    
    public function getCollaudoRuoli($where = null){
        $result = array();
        $temp = parent::getObjectsDAO($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                array_push($result, $this->getObj($item));
            }
        }
        return $result;
    }
    
    public function deleteObject($array): bool{
        return parent::deleteObject($array);
    }
    
    public function deleteObjectByID($ID) {
        parent::deleteObjectByID($ID);
    }
    
    public function update(MyObject $o){
        $obj = updateToColRuo($o);
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
