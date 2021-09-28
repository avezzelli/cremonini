<?php

namespace zecchini;

//In questa pagina posso vedere solamente i cantieri di giurisdizione dell'utente
//Bisogna effettuare una query che imposta il cantiere a seconda dell'utente in questione

//Se admin (vedo tutto)
//Se cliente (vedo i cantieri del brand relativo)
//Se cantierista (vedo i cantieri di riferimento dell'azienda)

$viewBrand = new CantiereView();

if(is_user_logged_in()){
    if(isAdmin()){
        //query su tutti i brand
    }
    
    
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}

