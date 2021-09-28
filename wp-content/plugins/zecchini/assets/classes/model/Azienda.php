<?php

namespace zecchini;

class Azienda extends MyObject{
    private $ragioneSociale;
    private $indirizzo;
    private $referente;
    private $telefono;
    private $email;
    private $pIva;
    private $utentiC;
    private $approvato;
    private $idUWP;
    
    function __construct() {
        parent::__construct();
    }
    
    function getRagioneSociale() {
        return $this->ragioneSociale;
    }

    function getIndirizzo() {
        return $this->indirizzo;
    }

    function getReferente() {
        return $this->referente;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getEmail() {
        return $this->email;
    }

    function setRagioneSociale($ragioneSociale) {
        $this->ragioneSociale = $ragioneSociale;
    }

    function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
    }

    function setReferente($referente) {
        $this->referente = $referente;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function getPIva() {
        return $this->pIva;
    }

    function setPIva($pIva) {
        $this->pIva = $pIva;
    }

    function getUtentiC() {
        return $this->utentiC;
    }

    function setUtentiC($utentiC) {
        $this->utentiC = $utentiC;
    }
    
    function getApprovato() {
        return $this->approvato;
    }

    function getIdUWP() {
        return $this->idUWP;
    }

    function setApprovato($approvato): void {
        $this->approvato = $approvato;
    }

    function setIdUWP($idUtente): void {
        $this->idUWP = $idUtente;
    }



}
