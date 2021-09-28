<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new BrandView();
    
    $viewBrand->listenerSaveForm();
    $viewBrand->printSaveForm();
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}