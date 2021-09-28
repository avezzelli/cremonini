<?php

namespace zecchini;
 $viewBrand = new BrandView();
//In questa pagina posso far vedere solamente i brand di giurisdizione dell'utente

//Se admin, vedo tutti i brand
//Se cliente, vedo i brand collegati
//Se cantierista, vedo i brand collegati all'azienda che sono collegati al cantiere, che è collegato al brand

if(isAdmin()){
   
    clearSearchBox();
    $viewBrand->printBrandSearchBox();
    $viewBrand->listenerBrandSearchBox();
}
else if(isCliente()){   
   $result = getIdUtenteByIdWP(get_current_user_id());
   $cliente = updateToCliente($result[OBJ_CLIENTE]);   
   $viewBrand->printClienteBrands($cliente->getID());
   
}
else if(isCantierista()){
    $result = getIdUtenteByIdWP(get_current_user_id());
    var_dump($result);
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}