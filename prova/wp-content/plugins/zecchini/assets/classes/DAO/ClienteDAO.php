<?php

namespace zecchini;


class ClienteDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_CLIENTE);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToCliente($o);
        $where = array(
            array(
                'campo'     => DBT_AZIENDA_PIVA,
                'valore'    => $obj->getPartitaIva(),
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
        $obj = updateToCliente($o);
        return array(
            DBT_IDUTENTE            => $obj->getIdUtente(),
            DBT_AZIENDA_RSOCIALE    => $obj->getRagioneSociale(),
            DBT_AZIENDA_PIVA        => $obj->getPartitaIva()
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
        return array('%d', '%s', '%s');
    }

    public function getObj($item) {
        $obj = new Cliente();
        $obj->setID($item[DBT_ID]);
        $obj->setIdUtente($item[DBT_IDUTENTE]);
        $obj->setRagioneSociale(stripslashes($item[DBT_AZIENDA_RSOCIALE]));
        $obj->setPartitaIva(stripslashes($item[DBT_AZIENDA_PIVA]));
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset));
    }

    public function save(MyObject $o) {
        $obj = updateToCliente($o);
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
        $obj = updateToCliente($o);
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
