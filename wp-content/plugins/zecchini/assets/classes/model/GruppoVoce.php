<?php

namespace zecchini;

class GruppoVoce extends MyObject {
    private $titolo;
    private $stato;
    private $idCollaudo;
    private $voci; //array di voci
    private $ruoli; //array di ruoli associati
    private $visibilita; //array di visibilità;
    
    function __construct() {
        parent::__construct();
    }
    
    function getTitolo() {
        return $this->titolo;
    }

    function getStato() {
        return $this->stato;
    }

    function getIdCollaudo() {
        return $this->idCollaudo;
    }

    function getVoci() {
        return $this->voci;
    }

    function setTitolo($titolo) {
        $this->titolo = $titolo;
    }

    function setStato($stato) {
        $this->stato = $stato;
    }

    function setIdCollaudo($idCollaudo) {
        $this->idCollaudo = $idCollaudo;
    }

    function setVoci($voci) {
        $this->voci = $voci;
    }

    function getRuoli() {
        return $this->ruoli;
    }

    function setRuoli($ruoli) {
        $this->ruoli = $ruoli;
    }

    function getVisibilita() {
        return $this->visibilita;
    }

    function setVisibilita($visibilita) {
        $this->visibilita = $visibilita;
    }




}
