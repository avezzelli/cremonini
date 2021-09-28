<?php

namespace zecchini;

class VisibilitaDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_VISIBILITA);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }
    
    public function delete($array){
        parent::deleteObject($array);
    }

    public function exists(MyObject $o) {
        $obj = updateToVisibilita($o);
        $where = array(
            array(
                'campo'     => DBT_IDGV,
                'valore'    => $obj->getIdGruppoVoci(),
                'formato'   => 'INT'
            ),
            array(
                'campo'     => DBT_IDRC,
                'valore'    => $obj->getIdRuoloC(),
                'formato'   => 'INT'
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
        $obj = updateToVisibilita($o);
        return array(
            DBT_IDGV    => $obj->getIdGruppoVoci(),
            DBT_IDRC    => $obj->getIdRuoloC()
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
        return array('%d', '%d');
    }

    public function getObj($item) {
        $obj = new Visibilita();
        $obj->setID($item[DBT_ID]);
        $obj->setIdGruppoVoci($item[DBT_IDGV]);
        $obj->setIdRuoloC($item[DBT_IDRC]);
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToVisibilita($o);
        if(!$this->exists($obj)){
            $campi = $this->getArray($obj);
            $formato = $this->getFormato();
            return parent::saveObject($campi, $formato);
        }
        return -1;
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToVisibilita($o);
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
