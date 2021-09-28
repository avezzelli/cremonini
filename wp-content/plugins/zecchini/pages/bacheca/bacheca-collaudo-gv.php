<?php

namespace zecchini;

if(isAdmin() || isCantierista()){
    $viewCollaudo = new CollaudoView();
    
    $viewCollaudo->listenerBachecaCollaudo();
    
    if(isset($_GET['idc']) && isset($_GET['idgv'])){
        $idc = $_GET['idc'];
        $idGV = $_GET['idgv'];
        $temp = getCollaudo($idc);
        if($temp !== null){
            $c = updateToCollaudo($temp);
            $viewCollaudo->printBachecaGV($c, $idGV);
        }
    }
    
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}