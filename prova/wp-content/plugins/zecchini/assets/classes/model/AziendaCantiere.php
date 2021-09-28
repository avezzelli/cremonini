<?php

namespace zecchini;

class AziendaCantiere extends MyObject {
    private $idCantiere;
    private $idAzienda;
    
    function __construct() {
        parent::__construct();
    }
    
    function getIdCantiere() {
        return $this->idCantiere;
    }

    function getIdAzienda() {
        return $this->idAzienda;
    }

    function setIdCantiere($idCantiere) {
        $this->idCantiere = $idCantiere;
    }

    function setIdAzienda($idAzienda) {
        $this->idAzienda = $idAzienda;
    }


}
