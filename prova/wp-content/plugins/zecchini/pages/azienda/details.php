<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new AziendaView();
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $viewBrand->listenerDetailsForm();
        $viewBrand->listenerCantieristaSaveForm();
        $viewBrand->printDetailsForm($ID);
    }
    else{
        echo '<p>Azienda non presente nel sistema</p>';
    }
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}