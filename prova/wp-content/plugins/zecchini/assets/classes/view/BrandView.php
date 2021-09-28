<?php

namespace zecchini;

class BrandView extends PrinterView implements InterfaceView {
    private $brand;
    private $utente;    
    
    function __construct() {
        parent::__construct();
        $this->brand = new BrandController();
        $this->utente = new UtenteController();        
    }
    
    /************************ BRAND ***********************************/

    public function listenerSaveForm() {
        if(isset($_POST[FRM_SAVE.FRM_BRAND])){
            //ottengo il brand
            $brand = $this->checkFields();
            if($brand == null){
                return;
            }
            $save = $this->brand->save($brand);
            $this->printMessaggeAfterSave(LBL_BRAND, $save);
        }
    }
    
    public function printSaveForm() {
        parent::printStartAddForm(FRM_BRAND, true);
            //nome
            parent::printTextFormField(FRM_BRAND_NOME, LBL_NOME, true);
            //logo
            parent::printInputFileFormField(FRM_BRAND_LOGO, LBL_BRAND_LOGO);
            //cliente            
            parent::printChosenSelectFormField(FRM_BRAND_CLIENTE, LBL_CLIENTE, $this->utente->getAllClientiForForm());     
        parent::printEndAddForm(FRM_BRAND);
    }

    public function listenerDetailsForm() {
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_BRAND])){
            $brand = $this->checkFields();
            if($brand == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $brand = updateToBrand($brand);
            $update = $this->brand->update($brand);
            parent::printMessageAfterUpdate($update);
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_BRAND])){
            $delete = $this->brand->delete($_GET['ID']);
            parent::printMessageAfterDelete($delete);
        }
    }
    
    public function printDetailsForm($ID) {
    ?>
        <div role="tabpanel" class="tab-pane active" id="brand">
    <?php
        $temp = $this->brand->getBrandByID($ID);
        if($temp != null){
            $brand = updateToBrand($temp);
            parent::printStartDetailsForm(FRM_BRAND, true);
                parent::printHiddenFormField(FRM_ID, $brand->getID());
                parent::printTextFormField(FRM_BRAND_NOME, LBL_NOME, true, $brand->getNome());
                parent::printInputFileFormField(FRM_BRAND_LOGO, LBL_BRAND_LOGO, false, $brand->getLogo());
                parent::printChosenSelectFormField(FRM_BRAND_CLIENTE, LBL_CLIENTE, $this->utente->getAllClientiForForm(), false, $brand->getIdCliente());
            parent::printEndDetailsForm(FRM_BRAND);
        }
        else{
            echo '<p>Brand non trovato</p>';
        }
        ?>
        </div>    
        <?php  
    }

    
    
    protected function checkFields(){
        $errors = 0;
        $brand = new Brand();
        
        //ID
        if(isset($_POST[FRM_ID])){
            $brand->setID($_POST[FRM_ID]);
        }        
        //Nome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_BRAND_NOME, LBL_NOME) !== false){
            $brand->setNome(parent::checkRequiredSingleField(FRM_BRAND_NOME, LBL_NOME));
        }
        else{
            $errors++;
        }
        //Logo IMMAGINE
        $upload = parent::checkUploadFileField(FRM_BRAND_LOGO);
        if($upload != null){
            if($upload['error'] != false){
                parent::printErrorBoxMessage($upload['error']);
            }
            else{
                $brand->setLogo($upload['url']);
            }
        }
        else{
            if(isset($_POST[FRM_ID])){
                $temp = $this->brand->getBrandByID($_POST[FRM_ID]);
                $brand->setLogo($temp->getLogo());
            }
        }
        //cliente
        if(parent::checkSingleField(FRM_BRAND_CLIENTE) !== false){
            $brand->setIdCliente(parent::checkSingleField(FRM_BRAND_CLIENTE));
        }        
        
        if($errors > 0){
            return null;
        }
        
        return $brand;
    }
        
    public function listenerBrandSearchBox(){
        if(isset($_POST[FRM_SEARCH_SUBMIT])){
            $brands = $this->brand->search($_POST);
            $this->printBrandTableResult($brands);
        }        
    }
    
    public function printBrandSearchBox(){
        parent::printStartSearchForm(FRM_BRAND);
            //nome
            parent::printTextFormField(FRM_BRAND_NOME, LBL_NOME);
            //idCliente
            parent::printChosenSelectFormField(FRM_BRAND_CLIENTE, LBL_CLIENTE, $this->utente->getAllClientiForForm());  
        
        parent::printEndSearchForm(FRM_BRAND);
    }
    
    protected function printBrandTableResult($array){
        if(checkResult($array)){
            echo '<h4>Brands trovati: '.count($array).'</h4>';
            $header = array(LBL_BRAND_LOGO, LBL_NOME, LBL_CLIENTE, 'AZIONI');
            $rows = array();
            foreach($array as $item){
                $brand = updateToBrand($item);
                $colonne = array(
                    '<img src="'.$brand->getLogo().'" />',
                    $brand->getNome(),
                    getNomeCliente($brand->getIdCliente()),
                    parent::getDetailsButton('dettaglio-'.FRM_BRAND, $brand->getID())
                );
                array_push($rows, $colonne);
            }
            parent::printTableHover($header, $rows);
        }
        else{
            parent::printNoResults(LBL_BRAND);
        }
    }
    
    public function printClienteBrands($idCliente){
        //stampo i brand a cui può accedere il cliente
        $brands = $this->brand->getBrandByCliente($idCliente);
        $this->printBrands($brands);
    }
    
    public function printUtentecBrands($idUC){
        
    }
    
    private function printBrands($brands){
        if(checkResult($brands)){
            echo '<div class="container-brands">';
            foreach($brands as $item){
                $brand = updateToBrand($item);
                echo '<div class="brand">';
                echo '<a href="'. home_url().'/'.OBJ_BRAND.'/dettaglio-'.OBJ_BRAND.'/?ID='.$brand->getID().'">';
                echo '<img src="'.$brand->getLogo().'" />';
                echo '<strong>'.$brand->getNome().'</strong>';
                echo '</a>';
                echo '</div>';
            }
            
            echo '</div>';
        }
    }
    
    
    /************************ CLIENTE ***********************************/
        
    public function listenerClienteSaveForm(){
        if(isset($_POST[FRM_SAVE.FRM_CLIENTE])){
           
            //ottengo il cliente
            $cliente = $this->checkClienteFields();
            if($cliente == null){
                return null;
            }
            $cliente = updateToCliente($cliente);            
            if(isset($_POST[FRM_CLIENTE_PASS]) && $_POST[FRM_CLIENTE_PASS] != ''){
                //la password è indicata, quindi devo inserire l'utente WP
                $idUWp = $this->utente->createUtenteWP($cliente->getEmail(), $_POST[FRM_CLIENTE_PASS], RUOLO_CLIENTE);
                if (is_wp_error($idUWp)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idUWp->get_error_message());
                    return null;
                }
                $cliente->setUtenteWp($idUWp);                
            }            
            $save = $this->utente->saveCliente($cliente);
            $this->printMessaggeAfterSave(LBL_CLIENTE, $save);
        }
    }
    
    public function printClienteSaveForm(){
        parent::printStartAddForm(FRM_CLIENTE);
            //ragione sociale
            parent::printTextFormField(FRM_CLIENTE_RS, LBL_RAGIONES, true);
            //partita iva
            parent::printTextFormField(FRM_CLIENTE_PIVA, LBL_PIVA, true);
            //telefono
            parent::printTextFormField(FRM_CLIENTE_TELEFONO, LBL_TELEFONO, true);
            //email
            parent::printEmailFormField(FRM_CLIENTE_EMAIL, LBL_EMAIL, true);
            //password
            parent::printPasswordFormField(FRM_CLIENTE_PASS, LBL_PASSWORD);
            
        parent::printEndAddForm(FRM_CLIENTE);
    }
    
    public function listenerClienteDetailsForm(){
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_CLIENTE])){
            $cliente = $this->checkClienteFields();
            //var_dump($cliente);
            if($cliente == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $cliente = updateToCliente($cliente);
            //effettuo un controllo per capire se bisogna creare un utente WP o aggiornare la password
            if($cliente->getUtenteWp() != null && $_POST[FRM_CLIENTE_PASS] != ''){
                //c'è il capo user name e la password non è vuota
                //Devo aggiornare il campo password
                $this->utente->updateUtenteWP($_POST[FRM_CLIENTE_PASS], $cliente->getUtenteWp());
            }
            else if($cliente->getUtenteWp() == null && $_POST[FRM_CLIENTE_PASS] != ''){
                //non ho l'utente wp ma è stata impostata la password
                //Devo creare l'utente wp
                $idUWp = $this->utente->createUtenteWP($cliente->getEmail(), $_POST[FRM_CLIENTE_PASS], RUOLO_CLIENTE);
                if (is_wp_error($idUWp)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idUWp->get_error_message());
                    return null;                    
                }
                $cliente->setUtenteWp($idUWp);                
            }
            $update = $this->utente->updateCliente($cliente);
            parent::printMessageAfterUpdate($update);            
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_CLIENTE])){
            //Devo rimuovere il cliente dal brand
            $where = array(
                array(
                    'campo'     => DBT_IDCLIENTE,
                    'valore'    => $_GET['ID'],
                    'formato'   => 'INT'
                )
            );
            $temp = $this->brand->getBrands($where);
            if(checkResult($temp)){
                foreach($temp as $item){
                    $brand = updateToBrand($item);
                    $brand->setIdCliente(null);
                    $this->brand->update($brand);
                }
            }
            
            $delete = $this->utente->deleteCliente($_GET['ID']);
            parent::printMessageAfterDelete($delete);
        }
        
    }
    
    public function printClienteDetailsForm($ID){
        $temp = $this->utente->getClienteByID($ID);
        if($temp != null){
            $cliente = updateToCliente($temp);
            
            parent::printStartDetailsForm(FRM_CLIENTE);
                parent::printHiddenFormField(FRM_ID, $cliente->getID());
                 //ragione sociale
                parent::printTextFormField(FRM_CLIENTE_RS, LBL_RAGIONES, true, $cliente->getRagioneSociale());
                //partita iva
                parent::printTextFormField(FRM_CLIENTE_PIVA, LBL_PIVA, true, $cliente->getPartitaIva());
                //telefono
                parent::printTextFormField(FRM_CLIENTE_TELEFONO, LBL_TELEFONO, true, $cliente->getTelefono());
                //email
                parent::printEmailFormField(FRM_CLIENTE_EMAIL, LBL_EMAIL, true, $cliente->getEmail());
                
                if($cliente->getUtenteWp() != null && $cliente->getUtenteWp() != ''){
                    $user_info = get_userdata($cliente->getUtenteWp());
                    parent::printTextFormField('WP_USER_NAME', 'User Login', true, $user_info->user_login, true);
                }
                else{
                    echo '<strong> CREA ACCESSO UTENTE</strong>';
                }
                //password
                parent::printPasswordFormField(FRM_CLIENTE_PASS, LBL_PASSWORD);
            parent::printEndDetailsForm(FRM_CLIENTE);
            
            //LISTA DEI BRAND
            
            
        }
        else{
            echo '<p>Cliente non trovato</p>';
        }
    }
    
    
    public function printBrandsByIdCliente($idCliente){
        $brands = $this->brand->getBrandByCliente($idCliente);
            if(checkResult($brands)){
                echo '<div class="container-brands">';
                foreach($brands as $item){
                    $brand = updateToBrand($item);
                    echo '<div class="brand">';
                    echo '<a href="'.home_url().'/dettaglio-'.FRM_BRAND.'?ID='.$brand->getID().'">';
                    echo '<img src="'.$brand->getLogo().'" />';
                    echo '<strong>'.$brand->getNome().'</strong>';
                    echo '</a>';
                    echo '</div>';                    
                }
                echo '</div>';
            }
    }
    
    public function listenerSearchBox(){
        
        
        if(isset($_POST[FRM_SEARCH_SUBMIT])){
            $clienti = $this->utente->search($_POST);
            $this->printClienteTableResults($clienti);
        }
    }
    
    public function printClienteSearchBox(){
        
        //ottengo i clienti
        $temp = $this->utente->getAllClienti();
        if(checkResult($temp)){
            //ho trovato dei clienti
            $cliente = updateToCliente($temp[0]);
            $this->printClienteDetailsForm($cliente->getID());
            
            //stampo il form di salva responsabili
            echo '<h3>Responsabili</h3>';
            //lista dei responsabili già inseriti
            $this->printResponsabileTableResult($this->utente->getResponsabiliCliente());
            
            //form di salvataggio
            $this->printResponsabileSaveForm();
            
            return $cliente->getID();
        }
        else{
            //non ho trovato clienti
            //stampo il save form
            $this->printSaveForm();
        }
        
        return null;
        
        /*
        parent::printStartSearchForm(FRM_CLIENTE);
            //Ragione sociale
            parent::printTextFormField(FRM_CLIENTE_RS, LBL_RAGIONES);   
            //Partita IVA
            parent::printTextFormField(FRM_CLIENTE_PIVA, LBL_PIVA);
            
        parent::printEndSearchForm(FRM_CLIENTE);
         * *
         */
    }
    
        
    protected function checkClienteFields(){
        $errors = 0;
        $cliente = new Cliente();
        
        //ID
        if(isset($_POST[FRM_ID])){
            $cliente->setID($_POST[FRM_ID]);
            $temp = updateToCliente($this->utente->getClienteByID($_POST[FRM_ID]));            
            //setto l'idUtente --> IMPORTANTISSIMO
            $cliente->setIdUtente($temp->getIdUtente());            
            //ottengo anche l'utente WP se esiste            
            $cliente->setUtenteWp($temp->getUtenteWp());
        }
        //Ragione sociale - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CLIENTE_RS, LBL_RAGIONES) !== false){
            $cliente->setRagioneSociale(parent::checkRequiredSingleField(FRM_CLIENTE_RS, LBL_RAGIONES));
        }
        else{
            $errors++;
        }
        //Partita IVA - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CLIENTE_PIVA, LBL_PIVA) !== false){
            $cliente->setPartitaIva(parent::checkRequiredSingleField(FRM_CLIENTE_PIVA, LBL_PIVA));
        }
        else{
            $errors++;
        }
        //telefono - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CLIENTE_TELEFONO, LBL_TELEFONO) !== false){
            $cliente->setTelefono(parent::checkRequiredSingleField(FRM_CLIENTE_TELEFONO, LBL_TELEFONO));
        }
        else{
            $errors++;
        }
        //email - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CLIENTE_EMAIL, LBL_EMAIL) !== false){
            $cliente->setEmail(parent::checkRequiredSingleField(FRM_CLIENTE_EMAIL, LBL_EMAIL));
        }
        else{
            $errors++;
        }       
        
        
        
        if($errors > 0){
            return null;
        }        
        return $cliente;        
    }
    
    protected function printClienteTableResults($array) {
        if(checkResult($array)){
            echo '<h4>Clienti trovati: '.count($array).'</h4>';
            $header = array(LBL_RAGIONES, LBL_PIVA, LBL_TELEFONO, LBL_EMAIL, 'AZIONI');
            $rows = array();
            
            foreach($array as $item){
                $cliente = updateToCliente($item);
                $colonne = array(
                    $cliente->getRagioneSociale(),
                    $cliente->getPartitaIva(),
                    $cliente->getTelefono(),
                    $cliente->getEmail(),
                    parent::getDetailsButton('dettaglio-'.FRM_CLIENTE, $cliente->getID())
                );                
                array_push($rows, $colonne);
            }
            parent::printTableHover($header, $rows);            
        }
        else{
            parent::printNoResults(LBL_CLIENTE);
        }
    }
    
    
    /***************************  RESPONSABILE PROPRIETARIO ***************************/
    
    public function listenerResponsabileSaveForm(){
        if(isset($_POST[FRM_SAVE.FRM_UTENTEC])){
            //ottengo il responsabile
            $utenteC = $this->checkResponsabileFields();
            if($utenteC == null){
                return null;
            }
            $utenteC = updateToUtenteC($utenteC);
            if(isset($_POST[FRM_UTENTEC_PASS]) && $_POST[FRM_UTENTEC_PASS] != ''){
                //password indicata, quindi inserisco l'utente WP
                $idWP = $this->utente->createUtenteWP($utenteC->getEmail(), $_POST[FRM_UTENTEC_PASS], RUOLO_CANTIERISTA);
                if(is_wp_error($idWP)){
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idWP->get_error_message());
                    return null;
                }
                $utenteC->setUtenteWp($idWP);
            }
            $save = $this->utente->saveUtenteC($utenteC);
            $this->printMessaggeAfterSave('Responsabile', $save);
        }
    }
    
    public function printResponsabileSaveForm(){
        parent::printStartAddForm(FRM_UTENTEC);
            parent::printHiddenFormField(DBT_IDAZIENDA, 0);
            //cognome
            parent::printTextFormField(FRM_UTENTEC_COGNOME, LBL_COGNOME, true);
            //nome
            parent::printTextFormField(FRM_UTENTEC_NOME, LBL_NOME, true);
            //telefono
            parent::printTextFormField(FRM_UTENTEC_TELEFONO, LBL_TELEFONO, true);
            //email
            parent::printEmailFormField(FRM_UTENTEC_EMAIL, LBL_EMAIL, true);
            //password
            parent::printPasswordFormField(FRM_UTENTEC_PASS, LBL_PASSWORD);
        parent::printEndAddForm(FRM_UTENTEC);
    }
    
    protected function printResponsabileTableResult($array){
        if(checkResult($array)){
            $header = array(LBL_COGNOME, LBL_NOME, LBL_TELEFONO, LBL_EMAIL, 'AZIONI');
            $rows = array();
            
            foreach($array as $item){
                $utenteC = updateToUtenteC($item);
                $colonne = array(
                    $utenteC->getCognome(),
                    $utenteC->getNome(),
                    $utenteC->getTelefono(),
                    $utenteC->getEmail(),
                    parent::getDetailsButton('dettaglio-cantierista', $utenteC->getID())
                );
                array_push($rows, $colonne);
            }
            parent::printTableHover($header, $rows);
        }
    }
    
    protected function checkResponsabileFields(){
        $errors = 0;
        $utenteC = new UtenteC();
        //ID UtenteC
        if(isset($_POST[FRM_ID])){
            $utenteC->setID($_POST[FRM_ID]);
            $temp = updateToUtenteC($this->utente->getUtenteCById($_POST[FRM_ID]));
            //setto l'azienda (nel dettaglio form)
            $utenteC->setIdAzienda($temp->getIdAzienda());
            //setto l'idUtente
            $utenteC->setIdUtente($temp->getIdUtente());
            //ottengo anche l'utente WP (se esiste)
            $utenteC->setUtenteWp($temp->getUtenteWp());
        }
        //id azienda
        if(isset($_POST[DBT_IDAZIENDA])){
            $utenteC->setIdAzienda($_POST[DBT_IDAZIENDA]);
        }
        //cognome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_UTENTEC_COGNOME, LBL_COGNOME) !== false){
            $utenteC->setCognome(parent::checkRequiredSingleField(FRM_UTENTEC_COGNOME, LBL_COGNOME));
        }
        else{
            $errors++;
        }
        //nome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_UTENTEC_NOME, LBL_NOME) !== false){
            $utenteC->setNome(parent::checkRequiredSingleField(FRM_UTENTEC_NOME, LBL_NOME));
        }
        else{
            $errors++;
        }
        //Telefono - obbligatorio
        if(parent::checkRequiredSingleField(FRM_UTENTEC_TELEFONO, LBL_TELEFONO) !== false){
            $utenteC->setTelefono(parent::checkRequiredSingleField(FRM_UTENTEC_TELEFONO, LBL_TELEFONO));
        }
        else{
            $errors++;
        }
        //Email - obbligatorio
        if(parent::checkRequiredSingleField(FRM_UTENTEC_EMAIL, LBL_EMAIL) !== false){
            $utenteC->setEmail(parent::checkRequiredSingleField(FRM_UTENTEC_EMAIL, LBL_EMAIL));
        }
        else{
            $errors++;
        }
        if($errors > 0){
            return null;
        }
        return $utenteC;
    }

}
