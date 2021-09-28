<?php

namespace zecchini;

class CollaudoDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_COLLAUDO);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToCollaudo($o);
        $where = array(
            array(
                'campo'     => DBT_NOME,
                'valore'    => $obj->getNome(),
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
        $obj = updateToCollaudo($o);
        return array(
            DBT_COLLAUDO_TIPO   => $obj->getTipo(),
            DBT_NOME            => $obj->getNome(),
            DBT_IDCANTIERE      => $obj->getIdCantiere(),
            DBT_COLLAUDO_DATA   => translateToTimestamp($obj->getDataCollaudo()),
            DBT_NOTE            => $obj->getNote(),
            DBT_COLLAUDO_BRAND  => $obj->getBrand(),
            DBT_COLLAUDO_LOCALE => $obj->getLocale(),
            DBT_STATO           => $obj->getStato(),
            DBT_COLLAUDO_PDF    => $obj->getPdf()
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
        return array('%d', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s');
    }

    public function getObj($item) {
        $obj = new Collaudo();
        $obj->setID($item[DBT_ID]);
        $obj->setTipo($item[DBT_COLLAUDO_TIPO]);
        $obj->setNome(stripslashes($item[DBT_NOME]));
        $obj->setIdCantiere($item[DBT_IDCANTIERE]);
        $obj->setDataCollaudo(translateToDate($item[DBT_COLLAUDO_DATA]));
        $obj->setNote(stripslashes($item[DBT_NOTE]));
        $obj->setBrand(stripslashes($item[DBT_COLLAUDO_BRAND]));
        $obj->setLocale(stripslashes($item[DBT_COLLAUDO_LOCALE]));
        $obj->setStato($item[DBT_STATO]);
        $obj->setPdf($item[DBT_COLLAUDO_PDF]);
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToCollaudo($o);        
        $campi = $this->getArray($obj);
        $formato = $this->getFormato();
        return parent::saveObject($campi, $formato);  
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToCollaudo($o);        
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
