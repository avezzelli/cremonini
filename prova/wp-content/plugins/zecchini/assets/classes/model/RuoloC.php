<?php

namespace zecchini;

class RuoloC extends MyObject {
    private $nome;
    function __construct() {
        parent::__construct();
    }
    
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
}
