<?php

namespace zecchini;

$viewCantiere = new CantiereView();

if(isAdmin()){
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $viewCantiere->listenerDetailsForm();
        $viewCantiere->printDetailsForm($ID);        
    }
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}