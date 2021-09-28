<?php

namespace zecchini;

if(isAdmin()){
    $viewBrand = new BrandView();
    $viewBrand->listenerClienteDetailsForm();
    $viewBrand->listenerResponsabileSaveForm();
?>
    <ul id="navCliente" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active">
            <a href="#cliente" aria-controls="cliente" role="tab" data-toggle="tab">Proprietario</a>
        </li>
        <li role="presentation">
            <a href="#brands" aria-controls="brands" role="tab" data-toggle="tab">Brands</a>
        </li>
    </ul>

    <div class="tab-content container-cliente-tab">
        <div role="tabpanel" class="tab-pane active" id="cliente">
            <?php $idCliente = $viewBrand->printClienteSearchBox(); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="brands">
            <h4>Lista Brand</h4>
            <?php 
                if($idCliente != null){
                    $viewBrand->printBrandsByIdCliente($idCliente);
                }
            ?>
            
        </div>
    </div>
<?php        
}
else{
    echo '<p>Non sei autorizzato a visualizzare questa pagina</p>';
}