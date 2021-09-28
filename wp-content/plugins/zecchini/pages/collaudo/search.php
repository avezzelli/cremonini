<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new CollaudoView();
    $viewBrand->printListCollaudi();
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}
