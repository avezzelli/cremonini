<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new AziendaView();
    clearSearchBox();
    $viewBrand->printsearchBox();
    $viewBrand->listenerSearchBox();
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}