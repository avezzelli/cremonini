<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new BrandView();
    
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        
        $viewBrand->listenerClienteDetailsForm();
        $viewBrand->printClienteDetailsForm($ID);
    }
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}