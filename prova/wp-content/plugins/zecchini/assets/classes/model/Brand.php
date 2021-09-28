<?php

namespace zecchini;

class Brand extends MyObject{
    private $nome;
    private $logo;
    private $idCliente;
    
    private $cantieri; //array di cantieri
    
    function __construct() {
        parent::__construct();
    }
    
    function getNome() {
        return $this->nome;
    }

    function getLogo() {
        return $this->logo;
    }

    function getIdCliente() {
        return $this->idCliente;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setLogo($logo) {
        $this->logo = $logo;
    }

    function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    function getCantieri() {
        return $this->cantieri;
    }

    function setCantieri($cantieri) {
        $this->cantieri = $cantieri;
    }



}
