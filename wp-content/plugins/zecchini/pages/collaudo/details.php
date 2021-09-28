<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new CollaudoView();
?>    

    <?php if(isset($_GET['ID'])){ 
            $ID = $_GET['ID'];
            $temp = getCollaudo($ID);
            if($temp !== null){
            
                $viewBrand->listenerDetailsForm();        
                $viewBrand->listenerGVSaveForm();        
                $viewBrand->listenerGVDetailForm();
            
    ?>

    <ul id="navCollaudo" class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#collaudo" aria-controls="collaudo" role="tab" data-toggle="tab">Dettagli Collaudo</a>
        </li>
        <li role="presentation">
            <a href="#salvagruppovoce" aria-controls="salvagruppovoce" role="tab" data-toggle="tab">Salva Gruppi Voce</a>
        </li>
        <li role="presentation">
            <a href="#dettagligruppovoce" aria-controls="dettagligruppovoce" role="tab" data-toggle="tab">Dettagli Gruppi Voce</a>
        </li>
    </ul>
    <div class="tab-content container-collaudo-tab">
        <?php $viewBrand->printDetailsForm($ID); ?>
        <?php $viewBrand->printGVSaveForm($ID); ?>
        <?php $viewBrand->printGVDetailForm($ID); ?>
    </div>
    
    
    <?php 
            }
            else{
                echo '<p>Collaudo non presente nel sistema</p>';
            }
    }
    
    ?>
    

<?php
}
else {
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}