<?php

namespace zecchini;


class CommentoDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_COMMENTO);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }
    
    public function delete($array){
        return parent::deleteObject($array);
    }

    public function exists(MyObject $o) {
        //non ha senso questa funzione per i commenti
    }

    public function getArray(MyObject $o) {
        $obj = updateToCommento($o);
        date_default_timezone_set('Europe/Rome');
        if($obj->getDataPubblicazione() != null){
            $data = date('Y-m-d H:i:s', convertToTimestamp($obj->getDataPubblicazione()));
        }
        else{
            $data = date('Y-m-d H:i:s');
        }
        //$data = translateToTimestamp2($obj->getDataPubblicazione());
        //print_r($data);
        return array(
            DBT_COMMENTO_AUTORE         => $obj->getAutore(),
            DBT_COMMENTO_CONTENUTO      => $obj->getContenuto(),
            DBT_COMMENTO_DPUBBLICAZIONE => $data,
            DBT_COMMENTO_IDPADRE        => $obj->getIdPadre(),
            DBT_IDVOCE                  => $obj->getIdVoce()
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
        return array('%d', '%s', '%s', '%d', '%d');
    }

    public function getObj($item) {
        $obj = new Commento();
        $obj->setID($item[DBT_ID]);
        $obj->setAutore($item[DBT_COMMENTO_AUTORE]);
        $obj->setContenuto(stripslashes($item[DBT_COMMENTO_CONTENUTO]));
        $obj->setDataPubblicazione(translateToDate($item[DBT_COMMENTO_DPUBBLICAZIONE], true));
        $obj->setIdPadre($item[DBT_COMMENTO_IDPADRE]);
        $obj->setIdVoce($item[DBT_IDVOCE]);
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToCommento($o);
        $campi = $this->getArray($obj);
        $formato = $this->getFormato();
        return parent::saveObject($campi, $formato);
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToCommento($o);
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
