<?php

namespace zecchini;

class CantiereCollaudo extends MyObject{
    private $idCantiere;
    private $idCollaudo;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdCantiere() {
        return $this->idCantiere;
    }

    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function setIdCantiere($idCantiere) {
        $this->idCantiere = $idCantiere;
    }

    function setIdCollaudo($idCollaudo) {
        $this->idCollaudo = $idCollaudo;
    }


}
