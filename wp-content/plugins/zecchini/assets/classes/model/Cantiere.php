<?php

namespace zecchini;

class Cantiere extends MyObject {
    private $nome;
    private $indirizzo;
    private $idBrand;
    private $aziende; //array di aziende associate
    private $precollaudo;
    private $collaudo;
    private $stato;
    private $dataApertura;
    private $dataChiusura;
    
    function __construct() {
        parent::__construct();
    }
    
    function getNome() {
        return $this->nome;
    }

    function getIndirizzo() {
        return $this->indirizzo;
    }

    function getIdBrand() {
        return $this->idBrand;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
    }

    function setIdBrand($idBrand) {
        $this->idBrand = $idBrand;
    }

    function getAziende() {
        return $this->aziende;
    }

    function setAziende($aziende) {
        $this->aziende = $aziende;
    }

    function getPrecollaudo() {
        return $this->precollaudo;
    }

    function getCollaudo() {
        return $this->collaudo;
    }

    function setPrecollaudo($precollaudo) {
        $this->precollaudo = $precollaudo;
    }

    function setCollaudo($collaudo) {
        $this->collaudo = $collaudo;
    }

    function getStato() {
        return $this->stato;
    }

    function getDataApertura() {
        return $this->dataApertura;
    }

    function getDataChiusura() {
        return $this->dataChiusura;
    }

    function setStato($stato) {
        $this->stato = $stato;
    }

    function setDataApertura($dataApertura) {
        $this->dataApertura = $dataApertura;
    }

    function setDataChiusura($dataChiusura) {
        $this->dataChiusura = $dataChiusura;
    }


}
