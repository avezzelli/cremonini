<?php

namespace zecchini;

class VoceDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_VOCE);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }
    
    public function delete($array){
        return parent::deleteObject($array);
    }

    public function exists(MyObject $o) {
        //non esiste una funzione exists
    }

    public function getArray(MyObject $o) {
        $obj = updateToVoce($o);
        return array(
            DBT_VOCE_DESCRIZIONE        => $obj->getDescrizione(),
            DBT_VOCE_PESO               => $obj->getPeso(),
            DBT_STATO                   => $obj->getStato(),
            DBT_VOCE_DLRISOLUZIONE      => translateToTimestamp($obj->getDataLimiteRisoluzione()),
            DBT_VOCE_DVULTIMAZIONE      => translateToTimestamp($obj->getDataVerificaUltimazione()),
            DBT_IDGV                    => $obj->getIdGruppoVoce(),
            DBT_NOTE                    => $obj->getNote(),
            DBT_VOCE_TIPO               => $obj->getTipo(),
            DBT_VOCE_ESITO              => $obj->getEsito()
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
        return array('%s', '%f', '%d', '%s', '%s', '%d', '%s', '%d', '%s');
    }

    public function getObj($item) {
        $obj = new Voce();
        $obj->setID($item[DBT_ID]);
        $obj->setDescrizione(stripslashes($item[DBT_VOCE_DESCRIZIONE]));
        $obj->setPeso($item[DBT_VOCE_PESO]);
        $obj->setStato($item[DBT_STATO]);
        $obj->setDataLimiteRisoluzione(translateToDate($item[DBT_VOCE_DLRISOLUZIONE]));
        $obj->setDataVerificaUltimazione(translateToDate($item[DBT_VOCE_DVULTIMAZIONE]));
        $obj->setIdGruppoVoce($item[DBT_IDGV]);
        $obj->setNote(stripslashes($item[DBT_NOTE]));
        $obj->setTipo($item[DBT_VOCE_TIPO]);
        $obj->setEsito(stripslashes($item[DBT_VOCE_ESITO]));
        return $obj;
    }

    public function getResults($where = null, $order = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $order)); 
    }

    public function save(MyObject $o) {
        $obj = updateToVoce($o);
        $campi = $this->getArray($obj);
        $formato = $this->getFormato();
        return parent::saveObject($campi, $formato);
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToVoce($o);
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
