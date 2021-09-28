<?php

namespace zecchini;

class UtenteC extends Utente {
    private $idAzienda;
    private $idUtente;
    private $nome;
    private $cognome;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdAzienda() {
        return $this->idAzienda;
    }

    function getIdUtente() {
        return $this->idUtente;
    }

    function setIdAzienda($idAzienda) {
        $this->idAzienda = $idAzienda;
    }

    function setIdUtente($idUtente) {
        $this->idUtente = $idUtente;
    }

    function getNome() {
        return $this->nome;
    }

    function getCognome() {
        return $this->cognome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCognome($cognome) {
        $this->cognome = $cognome;
    }



}
