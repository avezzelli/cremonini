<?php

namespace zecchini;

class AziendaDAO extends ObjectDAO implements InterfaceDAO{
    function __construct() {
        parent::__construct(DBT_AZIENDA);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToAzienda($o);
        $where = array(
            array(
                'campo'     => DBT_AZIENDA_PIVA,
                'valore'    => $obj->getPIva(),
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
        $obj = updateToAzienda($o);
        return array(
            DBT_AZIENDA_RSOCIALE        => $obj->getRagioneSociale(),
            DBT_INDIRIZZO               => $obj->getIndirizzo(),
            DBT_AZIENDA_REFERENTE       => $obj->getReferente(),
            DBT_TELEFONO                => $obj->getTelefono(),
            DBT_EMAIL                   => $obj->getEmail(),
            DBT_AZIENDA_PIVA            => $obj->getPIva()
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
        return array('%s', '%s', '%s', '%s', '%s', '%s');
    }

    public function getObj($item) {
        $obj = new Azienda();
        $obj->setID($item[DBT_ID]);
        $obj->setRagioneSociale(stripslashes($item[DBT_AZIENDA_RSOCIALE]));
        $obj->setIndirizzo(stripslashes($item[DBT_INDIRIZZO]));
        $obj->setReferente(stripslashes($item[DBT_AZIENDA_REFERENTE]));
        $obj->setTelefono(stripslashes($item[DBT_TELEFONO]));
        $obj->setEmail(stripslashes($item[DBT_EMAIL]));
        $obj->setPIva(stripslashes($item[DBT_AZIENDA_PIVA]));
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToAzienda($o);
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
        $obj = updateToAzienda($o);
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
