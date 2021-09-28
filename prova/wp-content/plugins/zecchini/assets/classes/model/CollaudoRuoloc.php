<?php

namespace zecchini;

class CollaudoRuoloc extends MyObject{
    private $idCollaudo;
    private $idRuoloC;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function getIdRuoloC() {
        return $this->idRuoloC;
    }

    function setIdCollaudo($idCollaudo) {
        $this->idCollaudo = $idCollaudo;
    }

    function setIdRuoloC($idRuoloC) {
        $this->idRuoloC = $idRuoloC;
    }


}
