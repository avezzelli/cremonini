<?php

namespace zecchini;

class Voce extends MyObject {
    private $descrizione;
    private $peso;
    private $stato;
    private $dataLimiteRisoluzione;
    private $dataVerificaUltimazione;
    private $idGruppoVoce;
    private $commenti; //array di commenti
    private $note;
    private $tipo;
    private $esito;
    
    function __construct() {
        parent::__construct();
    }
    
    function getDescrizione() {
        return $this->descrizione;
    }

    function getPeso() {
        return $this->peso;
    }

    function getStato() {
        return $this->stato;
    }

    function getDataLimiteRisoluzione() {
        return $this->dataLimiteRisoluzione;
    }

    function getDataVerificaUltimazione() {
        return $this->dataVerificaUltimazione;
    }

    function getIdGruppoVoce() {
        return $this->idGruppoVoce;
    }

    function getCommenti() {
        return $this->commenti;
    }

    function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    function setPeso($peso) {
        $this->peso = $peso;
    }

    function setStato($stato) {
        $this->stato = $stato;
    }

    function setDataLimiteRisoluzione($dataLimiteRisoluzione) {
        $this->dataLimiteRisoluzione = $dataLimiteRisoluzione;
    }

    function setDataVerificaUltimazione($dataVerificaUltimazione) {
        $this->dataVerificaUltimazione = $dataVerificaUltimazione;
    }

    function setIdGruppoVoce($idGruppoVoce) {
        $this->idGruppoVoce = $idGruppoVoce;
    }

    function setCommenti($commenti) {
        $this->commenti = $commenti;
    }

    function getNote() {
        return $this->note;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getEsito() {
        return $this->esito;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setEsito($esito) {
        $this->esito = $esito;
    }


}
