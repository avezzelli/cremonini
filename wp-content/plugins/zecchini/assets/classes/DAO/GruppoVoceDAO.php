<?php

namespace zecchini;

class GruppoVoceDAO extends ObjectDAO implements InterfaceDAO{
    function __construct() {
        parent::__construct(DBT_GV);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToGV($o);
        $where = array(
            array(
                'campo'     => DBT_GV_TITOLO,
                'valore'    => $obj->getTitolo(),
                'formato'   => 'STRING'
            )
        );
        $temp = parent::getObjectsDAO($where);
        if($temp != null && count($temp) > 0){
            foreach($temp as $item){
                $obj = $this->getObj($item);
                return parent::existsID($obj->getID());
            }
        }
        return false;
    }

    public function getArray(MyObject $o) {
        $obj = updateToGV($o);
        return array(
            DBT_GV_TITOLO       => $obj->getTitolo(),
            DBT_STATO           => $obj->getStato(),
            DBT_IDCOLLAUDO      => $obj->getIdCollaudo()
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
        return array('%s', '%d', '%d');
    }

    public function getObj($item) {
        $obj = new GruppoVoce();
        $obj->setID($item[DBT_ID]);
        $obj->setTitolo(stripslashes($item[DBT_GV_TITOLO]));
        $obj->setStato($item[DBT_STATO]);
        $obj->setIdCollaudo($item[DBT_IDCOLLAUDO]);
        return $obj;
    }

    public function getResults($where = null, $order = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $order)); 
    }

    public function save(MyObject $o) {
        $obj = updateToGV($o);
        
        $campi = $this->getArray($obj);
        $formato = $this->getFormato();
        return parent::saveObject($campi, $formato);
        
       
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToGV($o);
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
