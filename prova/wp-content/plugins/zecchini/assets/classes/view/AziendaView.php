<?php

namespace zecchini;

class AziendaView extends PrinterView implements InterfaceView{
    private $azienda;
    private $utente;
    
    function __construct() {
        parent::__construct();
        $this->azienda = new AziendaController();
        $this->utente = new UtenteController();
    }
    
    
    /***************************** AZIENDA *************************/
    
    public function listenerDetailsForm() {
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_AZIENDA])){
            $azienda = $this->checkFields();
            if($azienda == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $azienda = updateToAzienda($azienda);
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
                //email
                parent::printEmailFormField(FRM_AZIENDA_EMAIL, LBL_EMAIL, true, $azienda->getEmail());
                //piva
                parent::printTextFormField(FRM_AZIENDA_PIVA, LBL_PIVA, true, $azienda->getPIva());
            parent::printEndDetailsForm(FRM_AZIENDA);
            
            echo '<hr />';
            if($azienda->getUtentiC() != null){
                echo '<h4>Cantieristi</h4>';
                $this->printCantieristaTableResult($azienda->getUtentiC());
            }
            else{
                echo '<p>Nessun Cantierista trovato</p>';
            }
            echo '<hr/>';
            echo '<h4>Inserisci Cantieristi</h4>';
            $this->printCantieristaSaveForm($ID);
            
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
            $save = $this->azienda->save($azienda);
            $this->printMessaggeAfterSave(LBL_AZIENDA, $save);
        }
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
            //email
            parent::printEmailFormField(FRM_AZIENDA_EMAIL, LBL_EMAIL, true);
            //piva
            parent::printTextFormField(FRM_AZIENDA_PIVA, LBL_PIVA, true);
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
            
        parent::printEndSearchForm(FRM_AZIENDA);
    }
    
    protected function printAziendaTableResult($array){
        if(checkResult($array)){
            echo '<h4>Aziende trovate: '.count($array).'</h4>';
            $headers = array(LBL_RAGIONES, LBL_AZIENDA_REFERENTE, LBL_TELEFONO, LBL_EMAIL, 'AZIONI');
            $rows = array();
            foreach($array as $item){
                $azienda = updateToAzienda($item);
                $colonne = array(
                    $azienda->getRagioneSociale(),
                    $azienda->getReferente(),
                    $azienda->getTelefono(),
                    $azienda->getEmail(),
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
                $idWP = $this->utente->createUtenteWP($utenteC->getEmail(), $_POST[FRM_UTENTEC_PASS], RUOLO_CANTIERISTA);
                if (is_wp_error($idWP)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idWP->get_error_message());
                    return null;
                }
                //salvo nome e cognome nell'utente
                wp_update_user([
                    'ID'            => $idWP,
                    'first_name'    => $utenteC->getNome(),
                    'last_name'     => $utenteC->getCognome()
                ]);
                
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
                $this->utente->updateUtenteWP($_POST[FRM_UTENTEC_PASS], $utenteC->getUtenteWp());
            }
            else if($utenteC->getUtenteWp() == null && $_POST[FRM_UTENTEC_PASS] != '' ){
                //non ho l'utente wp ma è stata impostata la password
                //Devo creare l'utente wp
                $idUWp = $this->utente->createUtenteWP($utenteC->getEmail(), $_POST[FRM_UTENTEC_PASS], RUOLO_CANTIERISTA);
                if (is_wp_error($idUWp)) {
                    parent::printErrorBoxMessage($idUWp->get_error_code() .': '. $idUWp->get_error_message());
                    return null;                    
                }
                $utenteC->setUtenteWp($idUWp);
            }
            if($utenteC->getUtenteWp() != null && $utenteC->getUtenteWp() != ''){
                //aggiorno nome e cognome nell'utente
                wp_update_user([
                    'ID'            => $utenteC->getUtenteWp(),
                    'first_name'    => $utenteC->getNome(),
                    'last_name'     => $utenteC->getCognome()
                ]);
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
