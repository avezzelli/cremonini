<?php

namespace zecchini;

class Collaudo extends MyObject {
    private $tipo;
    private $nome;
    private $gruppoVoci; //array di gruppo voci
    private $idCantiere;
    private $ruoli; //array di ruoli associati al collaudo
    private $dataCollaudo;
    private $note;
    private $brand;
    private $locale;
    private $stato;
    private $responsabili; //array di utenti responsabili
    private $pdf;
    
    function __construct() {
        parent::__construct();
    }
    
    function getTipo() {
        return $this->tipo;
    }

    function getNome() {
        return $this->nome;
    }

    function getGruppoVoci() {
        return $this->gruppoVoci;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setGruppoVoci($gruppoVoci) {
        $this->gruppoVoci = $gruppoVoci;
    }
    
    function getIdCantiere() {
        return $this->idCantiere;
    }

    function setIdCantiere($idCantiere) {
        $this->idCantiere = $idCantiere;
    }
    
    function getRuoli() {
        return $this->ruoli;
    }

    function setRuoli($ruoli) {
        $this->ruoli = $ruoli;
    }
    
    function getDataCollaudo() {
        return $this->dataCollaudo;
    }

    function getNote() {
        return $this->note;
    }

    function getBrand() {
        return $this->brand;
    }

    function getLocale() {
        return $this->locale;
    }

    function setDataCollaudo($dataCollaudo) {
        $this->dataCollaudo = $dataCollaudo;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setBrand($brand) {
        $this->brand = $brand;
    }

    function setLocale($locale) {
        $this->locale = $locale;
    }

    function getStato() {
        return $this->stato;
    }

    function setStato($stato): void {
        $this->stato = $stato;
    }

    function getResponsabili() {
        return $this->responsabili;
    }

    function setResponsabili($responsabili): void {
        $this->responsabili = $responsabili;
    }

    function getPdf() {
        return $this->pdf;
    }

    function setPdf($pdf): void {
        $this->pdf = $pdf;
    }
 
}
