<?php

namespace zecchini;
$viewBrand = new BrandView();
$viewCantiere = new CantiereView();

if(isAdmin()){
    
    if(isset($_GET['ID'])){
        $ID = $_GET['ID'];
        $viewBrand->listenerDetailsForm();
?>        
    <ul id="navBrand" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active">
            <a href="#brand" aria-controls="brand" role="tab" data-toggle="tab">Dettagli Brand</a>
        </li>
        <li role="presentation">
            <a href="#cantieri" aria-controls="cantieri" role="tab" data-toggle="tab">Cantieri aperti</a>
        </li>
        <li role="presentation">
            <a href="#cantieri-chiusi" aria-controls="cantieri" role="tab" data-toggle="tab">Cantieri chiusi</a>
        </li>
        
    </ul>
    <div class="tab-content container-collaudo-tab">
        <?php $viewBrand->printDetailsForm($ID); ?>
        <?php $viewCantiere->printCantieriList($ID); ?>
        <?php $viewCantiere->printCantieriChiusiList($ID) ?>
    </div>

<?php
    }
    else{
        echo '<p>Brand non presente nel sistema</p>';
    }
    
    
    
    
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}
