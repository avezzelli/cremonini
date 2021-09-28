<?php

namespace zecchini;

class Utente extends MyObject{   
    private $telefono;
    private $email;
    private $utenteWp;
    private $ruoli;
    
    function __construct() {
        parent::__construct();
    }
    
    function getTelefono() {
        return $this->telefono;
    }

    function getEmail() {
        return $this->email;
    }

    function getUtenteWp() {
        return $this->utenteWp;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCognome($cognome) {
        $this->cognome = $cognome;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setUtenteWp($utenteWp) {
        $this->utenteWp = $utenteWp;
    }

    function getRuoli() {
        return $this->ruoli;
    }

    function setRuoli($ruoli) {
        $this->ruoli = $ruoli;
    }



}
