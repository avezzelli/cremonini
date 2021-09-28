<?php

namespace zecchini;

if(isAdmin() || isAzienda()){
    $viewBrand = new AziendaView();
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $viewBrand->listenerCantieristaDetailsForm();
        $viewBrand->printCantieristaDetailsForm($ID);
    }
    else{
        echo '<p>Referente non presente nel sistema</p>';
    }
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}
