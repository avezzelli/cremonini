<?php

namespace zecchini;

class AziendaView extends PrinterView implements InterfaceView{
    private $azienda;
    private $utente;
    private $approvato;
    
    function __construct() {
        parent::__construct();
        $this->azienda = new AziendaController();
        $this->utente = new UtenteController();
        $this->approvato = array(
            AZIENDA_APPROVATO_NO => 'No',
            AZIENDA_APPROVATO_SI => 'Si'
        );
    }
    
    
    /***************************** AZIENDA *************************/
    
    
    public function gestioneAziende(){
        //la funzione deve mostrare solamente la suddivisione delle aziende approvate e non
        //mediante la visualizzazione normale printtableresult
        
    ?>    
        <ul id="navBrand" class="nav nav-tabs" role="tablist"> 
            <li role="presentation" class="active">
                <a href="#attive" aria-controls="attive" role="tab" data-toggle="tab">Aziende approvate</a>
            </li>
            <li role="presentation">
                <a href="#nonattive" aria-controls="nonattive" role="tab" data-toggle="tab">Aziende non approvate</a>
            </li>
        </ul>
        
        <div class="tab-content aziende-collaudo-tab">
            <div role="tabpanel" class="tab-pane active" id="attive">
                <?php $this->printAziendaTableResult($this->azienda->getAziendeAttive()); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="nonattive">
                <?php $this->printAziendaTableResult($this->azienda->getAziendeNonAttive()); ?>
            </div>
        </div>            

    <?php
    }
    
    public function listenerDetailsForm() {
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_AZIENDA])){
            $azienda = $this->checkFields();
            if($azienda == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $azienda = updateToAzienda($azienda);
            //effettuo un controllo per capire se bisogna creare un utente WP o aggiornare la password
            if($azienda->getIdUWP() != null && $_POST[FRM_AZIENDA_PASSWORD] != ''){
                ///c'è l'utente wp e la password non è vuota
                //devo aggiornare il campo password
                $this->utente->updatePasswordUtenteWP($_POST[FRM_AZIENDA_PASSWORD], $azienda->getIdUWP());
            }
            else if($azienda->getIdUWP() == null && $_POST[FRM_AZIENDA_PASSWORD] != '' ){
                //non ho l'utente wp ma è stata impostata la password
                //Devo creare l'utente wp
                $idUWp = $this->utente->createUtenteWP($azienda->getEmail(), $_POST[FRM_AZIENDA_PASSWORD], RUOLO_AZIENDA, $azienda->getRagioneSociale());
                if (is_wp_error($idUWp)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idUWp->get_error_message());
                    return null;                    
                }
                $azienda->setIdUWP($idUWp);
            }
            if($azienda->getIdUWP() != null && $azienda->getIdUWP() != ''){
                $this->utente->aggiornaUtenteWP($azienda->getIdUWP(), $azienda->getRagioneSociale());
            }
            
            $update = $this->azienda->update($azienda);
            parent::printMessageAfterUpdate($update);
        }        
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_AZIENDA])){
            $delete = $this->azienda->deleteAziendaAndUtenti($_GET['ID']);
            parent::printMessageAfterDelete($delete);
        }
    }


    public function printDetailsForm($ID) {
        $temp = $this->azienda->getAziendaByID($ID);
        if($temp != null){        
            $azienda = updateToAzienda($temp);
            
            echo '<div role="tabpanel" class="tab-pane active" id="azienda">';
                parent::printStartDetailsForm(FRM_AZIENDA);
                    parent::printHiddenFormField(FRM_ID, $azienda->getID());                
                    //ragione sociale
                    parent::printTextFormField(FRM_AZIENDA_RAGIONES, LBL_RAGIONES, true, $azienda->getRagioneSociale());
                    //indirizzo
                    parent::printTextAreaFormField(FRM_AZIENDA_INDIRIZZO, LBL_AZIENDA_INDIRIZZO, true, $azienda->getIndirizzo());
                    //referente
                    parent::printTextFormField(FRM_AZIENDA_REFERENTE, LBL_AZIENDA_REFERENTE, false, $azienda->getReferente());
                    //telefono
                    parent::printTextFormField(FRM_AZIENDA_TELEFONO, LBL_TELEFONO, true, $azienda->getTelefono());                
                    //piva
                    parent::printTextFormField(FRM_AZIENDA_PIVA, LBL_PIVA, true, $azienda->getPIva());
                    //approvato
                    parent::printSelectFormField(FRM_AZIENDA_APPROVATO, LBL_AZIENDA_APPROVATO, $this->approvato, true, $azienda->getApprovato());   

                    //DATI PER UTENZA
                    //email
                    parent::printEmailFormField(FRM_AZIENDA_EMAIL, LBL_EMAIL, true, $azienda->getEmail());

                    if($azienda->getIdUWP() != null && $azienda->getIdUWP() != 0){
                        $user_info = get_userdata($azienda->getIdUWP());
                        parent::printTextFormField('WP_USER_NAME', 'User Login', true, $user_info->user_login, true);
                    }
                    else{
                        echo '<strong>CREA ACCESSO AZIENDA</strong>';
                    }
                    //password
                    parent::printPasswordFormField(FRM_AZIENDA_PASSWORD, LBL_PASSWORD);

                //l'eliminazione dell'azienda è consentita all'amministratore
                //ma non è consentita all'azienda stessa
                if(isAdmin()){    
                    parent::printEndDetailsForm(FRM_AZIENDA);
                }
                else if(isAzienda()){
                    parent::printEndDetailsForm(FRM_AZIENDA, true);
                }
            echo '</div>';
            
            echo '<div role="tabpanel" class="tab-pane" id="cantieristi">';
                if($azienda->getUtentiC() != null){
                    echo '<strong class="titolo">Referenti</strong>';
                    $this->printCantieristaTableResult($azienda->getUtentiC());
                }
                else{
                    echo '<p>Nessun Referente trovato</p>';
                }
                echo '<hr/>';
                echo '<strong class="titolo">Inserisci Referente</strong>';
                $this->printCantieristaSaveForm($ID);
            echo '</div>';
        }
        else{
            echo '<p>Azienda non trovata</p>';
        }
    }
    
    public function listenerSaveForm() {
        if(isset($_POST[FRM_SAVE.FRM_AZIENDA])){
            //ottengo l'azienda
            $azienda = $this->checkFields();
            if($azienda == null){
                return;
            }
            $azienda = updateToAzienda($azienda);
            if(isset($_POST[FRM_AZIENDA_PASSWORD]) && $_POST[FRM_AZIENDA_PASSWORD] != ''){
                //Password indicata, creo l'utente WP
                $idWP = $this->utente->createUtenteWP($azienda->getEmail(), $_POST[FRM_AZIENDA_PASSWORD], RUOLO_AZIENDA, $azienda->getRagioneSociale());
                if (is_wp_error($idWP)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idWP->get_error_message());
                    return null;
                }
                $azienda->setIdUWP($idWP);
            }
            //imposto lo stato di approvato
            if(isAdmin()){
                $azienda->setApprovato(AZIENDA_APPROVATO_SI);
            }
            else{
                $azienda->setApprovato(AZIENDA_APPROVATO_NO);
            }
            
            $save = $this->azienda->save($azienda);
            $this->printMessaggeAfterSave(LBL_AZIENDA, $save);
        }
    }
    
    public function listenerRegistraAzienda(){
        if(isset($_POST[FRM_SAVE.FRM_AZIENDA])){
            $azienda = $this->checkFields();
            if($azienda == null){
                return;
            }
            $azienda = updateToAzienda($azienda);
            if(isset($_POST[FRM_AZIENDA_PASSWORD]) && $_POST[FRM_AZIENDA_PASSWORD] != ''){
                //Password indicata, creo l'utente WP
                $idwp = $this->utente->createUtenteWP($azienda->getEmail(), $_POST[FRM_AZIENDA_PASSWORD], RUOLO_AZIENDA, $azienda->getRagioneSociale());
                
                if (is_wp_error($idwp)) {
                    parent::printErrorBoxMessage($idwp->get_error_code() .': '. $idwp->get_error_message());
                    return null;
                }
                $azienda->setIdUWP($idwp);
            }
            //imposto lo stato di approvato            
            $azienda->setApprovato(AZIENDA_APPROVATO_NO);
                        
            $save = $this->azienda->save($azienda);
            if($save > 0){
                //INVIO MAIL 
                $this->inviaEmailToAdmin();
                
                parent::printOkBoxMessage('Registrazione avvenuta con successo! Per poter accedere, attendi che un amministratore approvi la tua utenza.');
            }
            else{
                parent::printErrorBoxMessage('Qualcosa è andato storto...');
            }
        }
        
    }
    
    private function inviaEmailToAdmin(){
        $args = array(
            'role'    => 'administrator'           
        );
        $users = get_users( $args );
        $emails = array();
        foreach($users as $user){
            array_push($emails, $user->user_email);
        }
        
        $subject = 'Gestionale Cremonini - Richiesta registrazione nuova azienda';
        $message = '<p>&Egrave; pervenuta una nuova richiesta di registrazione di un\'azienda.</p><p>Controlla nell\'area amministrativa per convalidare o meno la nuova richiesta!</p>';        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $sent = wp_mail($emails, $subject, $message, $headers);
        if($sent){            
            return true;
        }        
        return false;
        
    }

    public function printSaveForm() {
        parent::printStartAddForm(FRM_AZIENDA);
            //ragione sociale
            parent::printTextFormField(FRM_AZIENDA_RAGIONES, LBL_RAGIONES, true);
            //indirizzo
            parent::printTextAreaFormField(FRM_AZIENDA_INDIRIZZO, LBL_AZIENDA_INDIRIZZO, true);
            //referente
            parent::printTextFormField(FRM_AZIENDA_REFERENTE, LBL_AZIENDA_REFERENTE);
            //telefono
            parent::printTextFormField(FRM_AZIENDA_TELEFONO, LBL_TELEFONO, true);            
            //piva
            parent::printTextFormField(FRM_AZIENDA_PIVA, LBL_PIVA, true);
            
            //DATI PER UTENZA
            //email
            parent::printEmailFormField(FRM_AZIENDA_EMAIL, LBL_EMAIL, true);
            //password
            parent::printPasswordFormField(FRM_AZIENDA_PASSWORD, LBL_PASSWORD);
            
        parent::printEndAddForm(FRM_AZIENDA);
    }
    
    public function listenerSearchBox(){
        if(isset($_POST[FRM_SEARCH_SUBMIT])){
            $aziende = $this->azienda->search($_POST);
            $this->printAziendaTableResult($aziende);            
        }
    }
    
    public function printsearchBox(){
                        
        parent::printStartSearchForm(FRM_AZIENDA);
            //ragione sociale
            parent::printTextFormField(FRM_AZIENDA_RAGIONES, LBL_RAGIONES);
            //piva
            parent::printTextFormField(FRM_AZIENDA_PIVA, LBL_PIVA);
            //referente
            parent::printTextFormField(FRM_AZIENDA_REFERENTE, LBL_AZIENDA_REFERENTE);
            //approvato
            parent::printSelectFormField(FRM_AZIENDA_APPROVATO, LBL_AZIENDA_APPROVATO, $this->approvato);            
            
        parent::printEndSearchForm(FRM_AZIENDA);
    }
    
    protected function printAziendaTableResult($array){
        
        if(checkResult($array)){
            echo '<strong class="titolo">Aziende trovate: '.count($array).'</strong>';
            $headers = array(LBL_RAGIONES, LBL_AZIENDA_REFERENTE, LBL_TELEFONO, LBL_EMAIL, LBL_AZIENDA_APPROVATO, 'AZIONI');
            $rows = array();
            foreach($array as $item){
                $azienda = updateToAzienda($item);
                $colonne = array(
                    $azienda->getRagioneSociale(),
                    $azienda->getReferente(),
                    $azienda->getTelefono(),
                    $azienda->getEmail(),
                    $this->approvato[$azienda->getApprovato()],
                    parent::getDetailsButton('dettaglio-'.FRM_AZIENDA, $azienda->getID())
                );
                array_push($rows, $colonne);
            }
            parent::printTableHover($headers, $rows);
        }
        else{
            parent::printNoResults(LBL_AZIENDA);
        }
    }
    
    
    protected function checkFields(){
        $errors = 0;
        $azienda = new Azienda();
        //ID
        if(isset($_POST[FRM_ID])){
            $azienda->setID($_POST[FRM_ID]);
            //se id azienda è imposato allora ottengo anche l'id utente
            $temp = updateToAzienda($this->azienda->getAziendaByID($_POST[FRM_ID]));
            if($temp->getIdUWP() != 0){
                $azienda->setIdUWP($temp->getIdUWP());
            }            
        }
        
        //idutente
        if(isset($_POST[DBT_IDUTENTE])){
            $azienda->setIdUWP($_POST[DBT_IDUTENTE]);
        }
        
        //ragione sociale - obbligatorio
        if(parent::checkRequiredSingleField(FRM_AZIENDA_RAGIONES, LBL_RAGIONES) !== false){
            $azienda->setRagioneSociale(parent::checkRequiredSingleField(FRM_AZIENDA_RAGIONES, LBL_RAGIONES));
        }
        else{
            $errors++;
        }
        //indirizzo - obbligatorio
        if(parent::checkRequiredSingleField(FRM_AZIENDA_INDIRIZZO, LBL_AZIENDA_INDIRIZZO) !== false){
            $azienda->setIndirizzo(parent::checkRequiredSingleField(FRM_AZIENDA_INDIRIZZO, LBL_AZIENDA_INDIRIZZO));
        }
        else{
            $errors++;
        }
        //referente - non obbligatorio
        if(parent::checkSingleField(FRM_AZIENDA_REFERENTE) !== false){
            $azienda->setReferente(parent::checkSingleField(FRM_AZIENDA_REFERENTE));
        }
        //telefono - obbligatorio
        if(parent::checkRequiredSingleField(FRM_AZIENDA_TELEFONO, LBL_TELEFONO) !== false){
            $azienda->setTelefono(parent::checkRequiredSingleField(FRM_AZIENDA_TELEFONO, LBL_TELEFONO));
        }
        else{
            $errors++;
        }
        //email - obbligatorio
        if(parent::checkRequiredSingleField(FRM_AZIENDA_EMAIL, LBL_EMAIL) !== false){
            $azienda->setEmail(parent::checkRequiredSingleField(FRM_AZIENDA_EMAIL, LBL_EMAIL));
        }
        else{
            $errors++;
        }
        //piva - obbligatorio
        if(parent::checkRequiredSingleField(FRM_AZIENDA_PIVA, LBL_PIVA) !== false){
            $azienda->setPIva(parent::checkRequiredSingleField(FRM_AZIENDA_PIVA, LBL_PIVA));
        }
        else{
            $errors++;
        }
        //approvato
        if(parent::checkSingleField(FRM_AZIENDA_APPROVATO) !== false){
            $azienda->setApprovato(parent::checkSingleField(FRM_AZIENDA_APPROVATO));
        }
             
        
        if($errors > 0){
            return null;
        }
        return $azienda;
    }
    
    /***************************** CANTIERISTA *************************/
    
    public function listenerCantieristaSaveForm(){
        if(isset($_POST[FRM_SAVE.FRM_UTENTEC])){
            //ottengo il cantierista            
            $utenteC = $this->checkCantieristaFields();
            if($utenteC == null){
                return null;
            }
            $utenteC = updateToUtenteC($utenteC);
            if(isset($_POST[FRM_UTENTEC_PASS]) && $_POST[FRM_UTENTEC_PASS] != ''){
                //La password è indicata, quindi devo inserire l'utente WP
                $idWP = $this->utente->createUtenteWP($utenteC->getEmail(), $_POST[FRM_UTENTEC_PASS], RUOLO_CANTIERISTA, $utenteC->getNome(), $utenteC->getCognome());
                if (is_wp_error($idWP)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idWP->get_error_message());
                    return null;
                }
                $utenteC->setUtenteWp($idWP);
            }
            $save = $this->utente->saveUtenteC($utenteC);
            $this->printMessaggeAfterSave(LBL_UTENTEC, $save);
        }
    }
    
    public function printCantieristaSaveForm($idAzienda){        
        parent::printStartAddForm(FRM_UTENTEC);
            parent::printHiddenFormField(DBT_IDAZIENDA, $idAzienda);
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
    
    public function listenerCantieristaDetailsForm(){
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_UTENTEC])){
            $utenteC = $this->checkCantieristaFields();           
            if($utenteC == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $utenteC = updateToUtenteC($utenteC);
            //effettuo un controllo per capire se bisogna creare un utente WP o aggiornare la password
            if($utenteC->getUtenteWp() != null && $_POST[FRM_UTENTEC_PASS] != ''){
                //c'è il capo user name e la password non è vuota
                //Devo aggiornare il campo password
                $this->utente->updatePasswordUtenteWP($_POST[FRM_UTENTEC_PASS], $utenteC->getUtenteWp());
            }
            else if($utenteC->getUtenteWp() == null && $_POST[FRM_UTENTEC_PASS] != '' ){
                //non ho l'utente wp ma è stata impostata la password
                //Devo creare l'utente wp
                $idUWp = $this->utente->createUtenteWP($utenteC->getEmail(), $_POST[FRM_UTENTEC_PASS], RUOLO_CANTIERISTA, $utenteC->getNome(), $utenteC->getCognome());
                if (is_wp_error($idUWp)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idUWp->get_error_message());
                    return null;                    
                }
                $utenteC->setUtenteWp($idUWp);
            }
            if($utenteC->getUtenteWp() != null && $utenteC->getUtenteWp() != ''){
                $this->utente->aggiornaUtenteWP($utenteC->getUtenteWp(), $utenteC->getNome(), $utenteC->getCognome());                
            }
            
            
            $update = $this->utente->updateUtenteC($utenteC);
            parent::printMessageAfterUpdate($update);
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_UTENTEC])){
            $delete = $this->utente->deleteUtenteC($_GET['ID']);
            parent::printMessageAfterDelete($delete);
        }
    }
    
    public function printCantieristaDetailsForm($ID){
        $temp = $this->utente->getUtenteCById($ID);
        
        if(isAzienda()){
            echo '<a href="'.home_url().'">Torna alla pagina precedente</a>';
        }
        if($temp != null){
            $utenteC = updateToUtenteC($temp);
            
            parent::printStartDetailsForm(FRM_UTENTEC);
                parent::printHiddenFormField(FRM_ID, $utenteC->getID());
                
                //Cognome
                parent::printTextFormField(FRM_UTENTEC_COGNOME, LBL_COGNOME, true, $utenteC->getCognome());
                //Nome
                parent::printTextFormField(FRM_UTENTEC_NOME, LBL_NOME, true, $utenteC->getNome());
                //telefono
                parent::printTextFormField(FRM_UTENTEC_TELEFONO, LBL_TELEFONO, true, $utenteC->getTelefono());
                //email
                parent::printEmailFormField(FRM_UTENTEC_EMAIL, LBL_EMAIL, true, $utenteC->getEmail());
                
                if($utenteC->getUtenteWp() != null && $utenteC->getUtenteWp() != ''){
                    $user_info = get_userdata($utenteC->getUtenteWp());
                    parent::printTextFormField('WP_USER_NAME', 'User Login', true, $user_info->user_login, true);
                }
                else{
                    echo '<strong> CREA ACCESSO UTENTE</strong>';
                }
                //password
                parent::printPasswordFormField(FRM_UTENTEC_PASS, LBL_PASSWORD);                
            parent::printEndDetailsForm(FRM_UTENTEC);
        }
        else{
            echo '<p>Referente non presente nel sistema</p>';
        }
        
    }
    
    protected function printCantieristaTableResult($array){
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
    
    protected function checkCantieristaFields(){
        $errors = 0;
        $utenteC = new UtenteC();
        //ID utente C
        if(isset($_POST[FRM_ID])){
            $utenteC->setID($_POST[FRM_ID]);
            $temp = updateToUtenteC($this->utente->getUtenteCById($_POST[FRM_ID]));
            //setto l'azienda (nel dettaglio form)
            $utenteC->setIdAzienda($temp->getIdAzienda());
            //setto l'idUtente
            $utenteC->setIdUtente($temp->getIdUtente());
            //ottengo ance l'utente WP se esiste
            $utenteC->setUtenteWp($temp->getUtenteWp());            
        }
        //Id azienda --> visibile quando si inserisce un utente nuovo nella pagina azienda
        if(isset($_POST[DBT_IDAZIENDA])){
            $utenteC->setIdAzienda($_POST[DBT_IDAZIENDA]);
        }    
        //Cognome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_UTENTEC_COGNOME, LBL_COGNOME) !== false){
            $utenteC->setCognome(parent::checkRequiredSingleField(FRM_UTENTEC_COGNOME, LBL_COGNOME));
        }
        else{
            $errors++;
        }
        //Nome - obbligatorio
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
