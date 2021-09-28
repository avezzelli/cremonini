<?php

namespace zecchini;

class UtentecRuoloc extends MyObject {
    private $idUtenteC;
    private $idRuoloC;
    private $idCollaudo;
    private $idAzienda;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdUtenteC() {
        return $this->idUtenteC;
    }

    function getIdRuoloC() {
        return $this->idRuoloC;
    }

    function setIdUtenteC($idUtenteC) {
        $this->idUtenteC = $idUtenteC;
    }

    function setIdRuoloC($idRuoloC) {
        $this->idRuoloC = $idRuoloC;
    }
    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function setIdCollaudo($idCantiere) {
        $this->idCollaudo = $idCantiere;
    }
    function getIdAzienda() {
        return $this->idAzienda;
    }

    function setIdAzienda($idAzienda) {
        $this->idAzienda = $idAzienda;
    }



}
