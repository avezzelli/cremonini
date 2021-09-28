<?php

namespace zecchini;

if(isAdmin()){
    $viewAzienda = new AziendaView();
    
    $viewAzienda->gestioneAziende();
}

