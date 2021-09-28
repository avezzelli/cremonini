<?php

namespace zecchini;

class Log extends MyObject {
    private $idGV;
    private $idVoce;
    private $stato;
    private $utenteWP;
    private $dataOperazione;
    private $idCollaudo;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdGV() {
        return $this->idGV;
    }

    function getIdVoce() {
        return $this->idVoce;
    }

    function getStato() {
        return $this->stato;
    }

    function getUtenteWP() {
        return $this->utenteWP;
    }

    function getDataOperazione() {
        return $this->dataOperazione;
    }

    function setIdGV($idGV): void {
        $this->idGV = $idGV;
    }

    function setIdVoce($idVoce): void {
        $this->idVoce = $idVoce;
    }

    function setStato($stato): void {
        $this->stato = $stato;
    }

    function setUtenteWP($utenteWP): void {
        $this->utenteWP = $utenteWP;
    }

    function setDataOperazione($dataOperazione): void {
        $this->dataOperazione = $dataOperazione;
    }
    
    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function setIdCollaudo($idCollaudo): void {
        $this->idCollaudo = $idCollaudo;
    }

}
