<?php

namespace zecchini;

class RuoloCDAO extends ObjectDAO implements InterfaceDAO {
    function __construct() {
        parent::__construct(DBT_RUOLOC);
    }

    public function deleteByID($ID) {
        return parent::deleteObjectByID($ID);
    }

    public function exists(MyObject $o) {
        $obj = updateToRuoloC($o);
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
        $obj = updateToRuoloC($o);
        return array(
            DBT_NOME        => $obj->getNome()                       
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
        return array('%s');
    }

    public function getObj($item) {
        $obj = new RuoloC();
        $obj->setID($item[DBT_ID]);
        $obj->setNome(stripslashes($item[DBT_NOME]));        
        return $obj;
    }

    public function getResults($where = null, $offset = null) {
        return $this->getArrayResult(parent::getObjectsDAO($where, $offset)); 
    }

    /**
     * La funzione restituisce in entrambi casi l'ID del ruolo che si vuole inserire
     * @param \zecchini\MyObject $o
     * @return type
     */
    public function save(MyObject $o) {
        $obj = updateToRuoloC($o);
        if(!$this->exists($obj)){
            $campi = $this->getArray($obj);
            $formato = $this->getFormato();
            return parent::saveObject($campi, $formato);
        }
        else{
            //se esiste restituisco l'id del ruolo
            $where = array(
                array(
                    'campo'     => DBT_NOME,
                    'valore'    => $obj->getNome(),
                    'formato'   => 'STRING'
                )            
            );
            $temp = $this->getResults($where);
            if($temp != null && count($temp) == 1){
                $temp2 = updateToRuoloC($temp[0]);
                return $temp2->getID();
            }
        }
        
    }

    public function search($query) {
        return $this->getArrayResult(parent::searchObjects($query));
    }

    public function update(MyObject $o) {
        $obj = updateToRuoloC($o);
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
