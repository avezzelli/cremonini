<?php

namespace zecchini;

class BrandDAO extends ObjectDAO implements InterfaceDAO{
    
    function __construct() {
        parent::__construct(DBT_BRAND);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToBrand($o);
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
        $obj = updateToBrand($o);
        return array(
            DBT_NOME        => $obj->getNome(),
            DBT_BRAND_LOGO  => $obj->getLogo(),
            DBT_IDCLIENTE   => $obj->getIdCliente()
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
        return array('%s', '%s', '%d');
    }

    public function getObj($item) {
        $obj = new Brand();
        $obj->setID($item[DBT_ID]);
        $obj->setNome(stripslashes($item[DBT_NOME]));
        $obj->setLogo(stripslashes($item[DBT_BRAND_LOGO]));
        $obj->setIdCliente($item[DBT_IDCLIENTE]);
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    public function save(MyObject $o) {
        $obj = updateToBrand($o);
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
        $obj = updateToBrand($o);
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
