<?php

namespace zecchini;

class Commento extends MyObject {
    private $autore;
    private $contenuto;
    private $dataPubblicazione;
    private $idPadre;
    private $idVoce;
    private $risposte; //array di risposte a tal commento (si identificano poi con delle query specifiche
    
    function __construct() {
        parent::__construct();
    }
    
    function getAutore() {
        return $this->autore;
    }

    function getContenuto() {
        return $this->contenuto;
    }

    function getDataPubblicazione() {
        return $this->dataPubblicazione;
    }

    function getIdPadre() {
        return $this->idPadre;
    }

    function getIdVoce() {
        return $this->idVoce;
    }

    function getRisposte() {
        return $this->risposte;
    }

    function setAutore($autore) {
        $this->autore = $autore;
    }

    function setContenuto($contenuto) {
        $this->contenuto = $contenuto;
    }

    function setDataPubblicazione($data_pubblicazione) {
        $this->dataPubblicazione = $data_pubblicazione;
    }

    function setIdPadre($id_padre) {
        $this->idPadre = $id_padre;
    }

    function setIdVoce($id_voce) {
        $this->idVoce = $id_voce;
    }

    function setRisposte($risposte) {
        $this->risposte = $risposte;
    }

}
