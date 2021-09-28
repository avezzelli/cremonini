<?php

namespace zecchini;
 
//Se l'utente non è loggato, allora gli chiedo di loggarsi

if ( is_user_logged_in() ) {
           
    printBenvenuto();
    
    if(isAdmin()){
        printBachecaAdmin();
    }
    else if(isCliente()){
        echo 'cliente';
    }
    else if(isCantierista()){        
        printBachecaCantierista();
    }
    else if(isAzienda()){        
        printBachecaAzienda();
    }
    
    
} else {
    $viewAzienda = new AziendaView();
    
    echo '<div class="col-xs-12 col-sm-6">';
    echo '<h3>Effettua il Login</h3>';
        //Login    
        wp_login_form();    
    echo '</div>';
    echo '<div class="col-xs-12 col-sm-6">';
    echo '<h3>Registrati come Azienda</h3>';
    echo '<p style="margin-top:10px; margin-bottom:15px; background:#fff; padding:15px; box-shadow: 2px 2px 5px #ddd; ">La registrazione attraverso questo form permette alle Aziende di identificarsi nel gestionale e creare i propri referenti.<br><br><strong>ATTENZIONE: </strong>La mail utilizzata per questa registrazione servirà esclusivamente per la gestione del profilo e dei referenti.</p>';
    
        $viewAzienda->listenerRegistraAzienda();
        //registra azienda
        if(!isset($_POST[FRM_SAVE.FRM_AZIENDA])){
            $viewAzienda->printSaveForm();
        }
    echo '</div>';
}
