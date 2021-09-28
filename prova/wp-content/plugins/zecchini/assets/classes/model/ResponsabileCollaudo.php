<?php

namespace zecchini;

class ResponsabileCollaudo extends MyObject{
    private $idUtenteC;
    private $idCollaudo;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdUtenteC() {
        return $this->idUtenteC;
    }

    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function setIdUtenteC($idUtenteC): void {
        $this->idUtenteC = $idUtenteC;
    }

    function setIdCollaudo($idCollaudo): void {
        $this->idCollaudo = $idCollaudo;
    }

}
