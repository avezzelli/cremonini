<?php


namespace zecchini;

class Cliente extends Utente {
    private $idUtente;   
    private $ragioneSociale;
    private $brand; //array di brand
    private $partitaIva;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdUtente() {
        return $this->idUtente;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }
  
    function getRagioneSociale() {
        return $this->ragioneSociale;
    }

    function setRagioneSociale($ragioneSociale) {
        $this->ragioneSociale = $ragioneSociale;
    }

    function getBrand() {
        return $this->brand;
    }

    function setBrand($brand) {
        $this->brand = $brand;
    }

    function getPartitaIva() {
        return $this->partitaIva;
    }

    function setPartitaIva($partitaIva) {
        $this->partitaIva = $partitaIva;
    }
}
