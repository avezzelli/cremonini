<?php

namespace zecchini;

class Visibilita extends MyObject {
    private $idGruppoVoci;
    private $idRuoloC;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdGruppoVoci() {
        return $this->idGruppoVoci;
    }

    function getIdRuoloC() {
        return $this->idRuoloC;
    }

    function setIdGruppoVoci($idGruppoVoci) {
        $this->idGruppoVoci = $idGruppoVoci;
    }

    function setIdRuoloC($idRuoloC) {
        $this->idRuoloC = $idRuoloC;
    }

}
