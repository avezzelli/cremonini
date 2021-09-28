<?php

namespace zecchini;

if(isAdmin() || isCantierista()){
    $viewCollaudo = new CollaudoView();
    
    $viewCollaudo->listenerBachecaCollaudo();
    
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $temp = getCollaudo($ID);
        if($temp !== null){
            $c = updateToCollaudo($temp);
            $viewCollaudo->printBachecaCollaudoTitoli($c);
        }
    }
    
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}


