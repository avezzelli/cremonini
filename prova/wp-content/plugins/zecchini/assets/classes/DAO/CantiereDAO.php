<?php

namespace zecchini;

class CantiereDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_CANTIERE);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToCantiere($o);
        $where = array(
            array(
                'campo'     => DBT_NOME,
                'valore'    => $obj->getNome(),
                'formato'   => 'STRING'
            ),
            array(
                'campo'     => DBT_INDIRIZZO,
                'valore'    => $obj->getIndirizzo(),
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
        $obj = updateToCantiere($o);
        return array(
            DBT_NOME                => $obj->getNome(),
            DBT_INDIRIZZO           => $obj->getIndirizzo(),
            DBT_IDBRAND             => $obj->getIdBrand(),
            DBT_STATO               => $obj->getStato(),
            DBT_CANTIERE_DAPERTURA  => translateToTimestamp($obj->getDataApertura()),
            DBT_CANTIERE_DCHIUSURA  => translateToTimestamp($obj->getDataChiusura())                
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
        return array('%s', '%s', '%d', '%d', '%s', '%s');
    }

    public function getObj($item) {
        $obj = new Cantiere();
        $obj->setID($item[DBT_ID]);
        $obj->setNome(stripslashes($item[DBT_NOME]));
        $obj->setIndirizzo(stripslashes($item[DBT_INDIRIZZO]));
        $obj->setIdBrand($item[DBT_IDBRAND]);
        $obj->setStato($item[DBT_STATO]);
        $obj->setDataApertura(translateToDate($item[DBT_CANTIERE_DAPERTURA]));
        $obj->setDataChiusura(translateToDate($item[DBT_CANTIERE_DCHIUSURA]));
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset));
    }

    public function save(MyObject $o) {
        $obj = updateToCantiere($o);
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
        $obj = updateToCantiere($o);
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
