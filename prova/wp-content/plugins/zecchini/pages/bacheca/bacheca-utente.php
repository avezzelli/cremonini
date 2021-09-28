<?php

namespace zecchini;
 
//Se l'utente non è loggato, allora gli chiedo di loggarsi

if ( is_user_logged_in() ) {
    
    if(isAdmin()){
        echo 'amministratore';
    }
    else if(isCliente()){
        echo 'cliente';
    }
    else if(isCantierista()){        
        printBachecaCantierista();
    }
    
    
} else {
    //user isn't logged in, create a login template and call from here
    //get_template_part ( 'content', 'login' ); //create your login form at content-login.php file
    //or you can use the wp built in function to load the default form
    wp_login_form();
}
