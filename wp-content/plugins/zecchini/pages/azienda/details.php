<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new AziendaView();
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $viewBrand->listenerDetailsForm();
        $viewBrand->listenerCantieristaSaveForm();
        
?>
    <ul id="navCliente" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active">
            <a href="#azienda" aria-controls="azienda" role="tab" data-toggle="tab">Dettagli Azienda</a>
        </li>
        <li role="presentation">
            <a href="#cantieristi" aria-controls="cantieristi" role="tab" data-toggle="tab">Referenti</a>
        </li>
    </ul>

    <div class="tab-content container-cliente-tab">
        <?php $viewBrand->printDetailsForm($ID); ?>
    </div>
<?php        
        
    }
    else{
        echo '<p>Azienda non presente nel sistema</p>';
    }
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}