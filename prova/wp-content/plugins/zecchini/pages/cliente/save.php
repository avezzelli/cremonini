<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new BrandView();
    
    $viewBrand->listenerClienteSaveForm();
    $viewBrand->printClienteSaveForm();
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}