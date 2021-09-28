<?php

namespace zecchini;

class UtenteCDAO extends ObjectDAO implements InterfaceDAO{
    function __construct() {
        parent::__construct(DBT_UC);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToUtenteC($o);
        $where = array(
            array(
                'campo'     => DBT_IDAZIENDA,
                'valore'    => $obj->getIdAzienda(),
                'formato'   => 'INT'
            ),
            array(
                'campo'     => DBT_IDUTENTE,
                'valore'    => $obj->getIdUtente(),
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
        $obj = updateToUtenteC($o);
        return array(
            DBT_IDAZIENDA   => $obj->getIdAzienda(),
            DBT_IDUTENTE    => $obj->getIdUtente(),
            DBT_NOME        => $obj->getNome(),
            DBT_COGNOME     => $obj->getCognome()
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
        return array('%d', '%d', '%s', '%s');
    }

    public function getObj($item) {
        $obj = new UtenteC();
        $obj->setID($item[DBT_ID]);
        $obj->setIdAzienda($item[DBT_IDAZIENDA]);
        $obj->setIdUtente($item[DBT_IDUTENTE]);
        $obj->setNome(stripslashes($item[DBT_NOME]));
        $obj->setCognome(stripslashes($item[DBT_COGNOME]));
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset));
    }

    public function save(MyObject $o) {
        $obj = updateToUtenteC($o);
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
        $obj = updateToUtenteC($o);
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
