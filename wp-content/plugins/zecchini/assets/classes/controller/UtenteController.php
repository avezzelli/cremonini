<?php

namespace zecchini;

class UtenteController implements InterfaceController{
    //utente controller deve gestire:
    private $uDAO; //utente DAO
    private $ucDAO; //utenteCantiere DAO
    private $cDAO; //cliente DAO   
    private $rDAO; //ruolo DAO
    private $urDAO; //utente ruolo DAO
    
    function __construct(){
        $this->uDAO = new UtenteDAO();
        $this->ucDAO = new UtenteCDAO();
        $this->cDAO = new ClienteDAO();        
        $this->rDAO = new RuoloCDAO();
        $this->urDAO = new UtentecRuolocDAO();
    }

    /******** UTENTE *************/
    
    private function copyToUtente(MyObject $o, $idUtente = null): Utente{
        $temp = updateToUtente($o);
        $result = new Utente();       
        $result->setTelefono($temp->getTelefono());
        $result->setEmail($temp->getEmail());
        $result->setUtenteWp($temp->getUtenteWp());
        if($idUtente != null){
            $result->setID($idUtente);
        }
        
        return $result;        
    }
    
    
    private function saveUtente(Utente $u){
        return $this->uDAO->save($u);
    }
    
    private function updateUtente(Utente $u){
        return $this->uDAO->update($u);
    }
    
    private function deleteUtente($idUtente){
        //devo eliminare prima l'utente wp se assegnato
        $obj = updateToUtente($this->getUtente($idUtente));
        if($obj->getUtenteWp() != null && $obj->getUtenteWp() != ''){
            wp_delete_user($obj->getUtenteWp());           
        }
        return $this->uDAO->deleteByID($idUtente);
    }
    
    private function getUtente($idUtente){        
        return $this->uDAO->getResultByID($idUtente);
    }
    
    /**
     * Aggiorna un utente settandogli l'ID dell'utente wordpress
     * @param type $idUWP
     * @param type $idUtente
     * @return type
     */
    private function addUtenteWP($idUWP, $idUtente){
        $u = updateToUtente($this->getUtente($idUtente));
        $u->setUtenteWp($idUWP);
        return $this->updateUtente($u);
    }
    
    /**
     * Restituisce un ID utente conoscendo l'ID WP
     * @param type $idUWP
     * @return type
     */
    private function getIdUtenteByIdWP($idUWP){        
        $where = array(
            array(
                'campo'     => DBT_UC_UW,
                'valore'    => $idUWP,
                'formato'   => 'INT'
            )
        );
        $temp = $this->uDAO->getResults($where);  
        
        if($temp != null && count($temp) == 1){
            //teoricamente l'associazione utente wp / utente è 1 a 1
            $u = updateToUtente($temp[0]);
            return $u->getID();
        }
        return null;
    }
    
    /**
     * Restituisce un oggetto Cliente o UtenteC a seconda dell'utente Wordpress passato
     * @param type $idWP
     * @return type
     */
    public function getUtenteByIdWP($idWP){        
        //la funzione deve restituire un utente suddiviso per ruolo
        //ottengo l'ID utente
        $idUtente = $this->getIdUtenteByIdWP($idWP);
        
        if($idUtente != null){
            //verifico se si tratta di un cliente
            $where = array(
                array(
                    'campo'     => DBT_IDUTENTE,
                    'valore'    => $idUtente,
                    'formato'   => 'INT'
                )
            );
            $temp1 = $this->getClienti($where);
            if(count($temp1) > 0){            
                $result[OBJ_CLIENTE] = updateToCliente($temp1[0]);
                return $result;
            }
            $temp2 = $this->getUtentiC($where);
            if(count($temp2) > 0){            
                $result[OBJ_UTENTEC] = updateToUtenteC($temp2[0]);
                return $result;
            }    
        }
        
        return null;        
    }
       
    /**** RUOLI CANTIERE ***/
    
    private function saveUtenteRuoli($ruoli, $idUC){
        if(checkResult($ruoli)){
            foreach($ruoli as $item){
                $ur = new UtentecRuoloc();
                $ur->setIdRuoloC($item);
                $ur->setIdUtenteC($idUC);
                if(!$this->urDAO->save($ur)){
                    return false;
                }
            }
        }            
        return true;
    }
    
    private function updateUtenteRuoli($ruoli, $idUC){
        //aggiorno i ruoli / utente
        //prima però devo cancellari quelli assegnati all'utente
        if(!$this->deleteUtenteRuoli($idUC)){
            return false;
        }
        //salvo i ruoli
        return $this->saveUtenteRuoli($ruoli, $idUC);
    }
    
    private function deleteUtenteRuoli($idUC){
        return $this->urDAO->deleteObject(array(DBT_IDUTENTEC => $idUC));
    }
    
    private function getUtenteRuoli($idUtente){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDUTENTEC,
                'valore'    => $idUtente,
                'formato'   => 'INT'
            )
        );
        $temp = $this->urDAO->getUtentiRuoli($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $rc = updateToUteRuo($item);
                array_push($result, $rc->getIdRuoloC());
            }
        }
        return $result;
    }
    
    /******** UTENTE CANTIERE *************/
    
    public function saveUtenteC(UtenteC $uc){        
        $idUtente = $this->saveUtente($this->copyToUtente($uc));
        if($idUtente > 0){
            $uc->setIdUtente($idUtente);
            $idUC = $this->ucDAO->save($uc);
            //salvo i ruoli se presenti
            return $this->saveUtenteRuoli($uc->getRuoli(), $idUC);            
        }
        return false;
    }
    
    public function updateUtenteC(UtenteC $uc){
        //aggiorno l'utente
        $this->updateUtente($this->copyToUtente($uc, $uc->getIdUtente()));        
        //aggiorno i ruoli 
        $this->updateUtenteRuoli($uc->getRuoli(), $uc->getID());        
        //aggiorno l'utente cantiere
        return $this->ucDAO->update($uc);
    }
    
    /**
     * Cancello un utente cantiere
     * @param type $ID
     * @return boolean
     */
    public function deleteUtenteC($ID){             
        $uc = $this->ucDAO->getResultByID($ID);
        if($uc != null){
            $uc = updateToUtenteC($uc);
        }
        //cancello l'utente
        if(!$this->deleteUtente($uc->getIdUtente())){
            return -1;
        }
        //cancello i ruoli
        if(!$this->deleteUtenteRuoli($uc->getID())){
            return -2;
        }
        //cancello utenteC
        if(!$this->ucDAO->deleteByID($ID)){
            return -3;
        }
        return true;
    }
    
    /**
     * Elimino tutti gli utenti di un'azienda
     * @param type $idAzienda
     * @return boolean
     */
    public function deleteAllUtentiByAzienda($idAzienda){
        $where = array(
            array(
                'campo'     => DBT_IDAZIENDA,
                'valore'    => $idAzienda,
                'formato'   => 'INT'
            )
        );
        $temp = $this->getUtentiC($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $uc = updateToUtenteC($item);
                //elimino l'utente wp se associato
                if($uc->getUtenteWp() != null){
                    $this->deleteUtenteWP($uc->getUtenteWp());
                }
                if(!$this->deleteUtenteC($uc->getID())){
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Ottengo gli Utenti Cantiere
     * @param type $where
     * @return array
     */
    public function getUtentiC($where){
        $result = array();
        $temp = $this->ucDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $uc = updateToUtenteC($item);
                //ottengo l'utente
                $u = updateToUtente($this->getUtente($uc->getIdUtente()));
                //copio l'utente nel utente cantiere
                $uc = $this->copyToUtenteC($u, $uc);                
                //ottengo i ruoli
                $uc->setRuoli($this->getUtenteRuoli($uc->getID()));
                array_push($result, $uc);
            }
        }        
        return $result;
    }
    
    public function getUtenteCById($ID){
        $where = array(
            array(
                'campo'     => DBT_ID,
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->getUtentiC($where);
        if($temp != null && count($temp) == 1){
            return $temp[0];
        }
        return null;
    }
    
    /**
     * La funzione copia un utente in un utentecantiere
     * @param \zecchini\Utente $u
     * @param \zecchini\UtenteC $uc
     * @return \zecchini\UtenteC
     */
    private function copyToUtenteC(Utente $u, UtenteC $uc): UtenteC{        
        $uc->setEmail($u->getEmail());
        $uc->setTelefono($u->getTelefono());
        $uc->setUtenteWp($u->getUtenteWp());        
        return $uc;
    }
    
    /**
     * La funzione restituisce gli utentic che hanno id azienda = 0,
     * cioè quelli che per via di un barbatrucco sono considerati responsabili
     * del proprietario
     * @return type
     */
    public function getResponsabiliCliente(){
        //ottengo i responsabili da chi ha id_Azienda = 0;
        
        $where = array(
            array(
                'campo'     => DBT_IDAZIENDA,
                'valore'    => 0,
                'formato'   => 'INT'
            )
        );
        return $this->getUtentiC($where);
    }
    
    
    /******** CLIENTE *************/
    
    public function saveCliente(Cliente $c){
        $idUtente = $this->saveUtente($this->copyToUtente($c));        
        if($idUtente > 0){
            $c->setIdUtente($idUtente);
            return $this->cDAO->save($c);
        }
        return false;
    }
    
    public function updateCliente(Cliente $c){
        //aggiorno l'utente
        $this->updateUtente($this->copyToUtente($c, $c->getIdUtente()));
        //aggiorno il cliente
        return $this->cDAO->update($c);
    }
    
    public function deleteCliente($ID){
        $c = $this->cDAO->getResultByID($ID);
        if($c != null){
            $c = updateToCliente($c);
        }                
        //cancello l'utente
        if(!$this->deleteUtente($c->getIdUtente())){
            return -1;
        }
        //cancello cliente
        if(!$this->cDAO->deleteByID($ID)){
            return -2;
        }
        return true;
    }
    
    /**
     * Ottengo i clienti
     * @param type $where
     * @return array
     */
    public function getClienti($where){
        $result = array();
        $temp = $this->cDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $c = updateToCliente($item);                  
                $u = updateToUtente($this->getUtente($c->getIdUtente()));
                //copio l'utente nel cliente
                $c = $this->copyToCliente($u, $c);
                array_push($result, $c);
            }
        }
        
        return $result;
    }
    
    public function getAllClienti(){
        $result = array();
        $temp = $this->cDAO->getResults();
        if(checkResult($temp)){
            foreach($temp as $item){
                $c = updateToCliente($item);                  
                $u = updateToUtente($this->getUtente($c->getIdUtente()));
                //copio l'utente nel cliente
                $c = $this->copyToCliente($u, $c);
                array_push($result, $c);
            }
        }
        
        return $result;
    }
    
    public function getClienteByID($ID){
        $where = array(
            array(
                'campo'     => DBT_ID,
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->getClienti($where);
        if($temp != null && count($temp) == 1){
            return $temp[0];
        }
        return null;
    }
    
    public function getAllClientiForForm(){
        $result = array();
        $clienti = $this->cDAO->getResults();        
        if(checkResult($clienti)){
            foreach($clienti as $item){
                $c = updateToCliente($item);
                $result[$c->getID()] = $c->getRagioneSociale();
            }
        }
        //ordino i clienti in ordine alfabetico
        asort($result);        
        return $result;
    }
    
    /**
     * La funzione copia un utente in un cliente
     * @param \zecchini\Utente $u
     * @param \zecchini\Cliente $c
     * @return \zecchini\Cliente
     */
    private function copyToCliente(Utente $u, Cliente $c):Cliente{        
        $c->setEmail($u->getEmail());
        $c->setTelefono($u->getTelefono());
        $c->setUtenteWp($u->getUtenteWp());
        return $c;
    }
    
    
    public function search($array, $offset = null){
        //2 valori da controllare: Ragione Sociale, PIVA        
        $where = array(
            array(
                'campo'     => DBT_AZIENDA_RSOCIALE,
                'valore'    => $array[FRM_CLIENTE_RS],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            ),
            array(
                'campo'     => DBT_AZIENDA_PIVA,
                'valore'    => $array[FRM_CLIENTE_PIVA],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            )
        );
        return $this->getClienti($where);
    }
    
        
    /********** RUOLO *************/
    
    public function saveRuolo(RuoloC $r){
        return $this->rDAO->save($r);
    }
    
    public function updateRuolo(RuoloC $r){
        return $this->rDAO->update($r);
    }
    
    public function deleteRuolo($ID){
        //cancellare un ruolo è rischioso se esistono degli utentiC associati
        //controllo prima
        $where = array(
            array(
                'campo'     => DBT_IDRC,
                'valore'    => $ID,
                'formato'   => 'INT'
            )
        );
        $temp = $this->urDAO->getUtentiRuoli($where);
        if(checkResult($temp)){
            return -1;
        }
        
        return $this->ucDAO->deleteByID($ID);
    }
    
    public function getRuoli($where){
        return $this->rDAO->getResults($where);
    }
    
    public function getAllRuoli(){
        return $this->rDAO->getResults();
    }
    
    public function getRuoloByID($idRuolo){         
        $result = null;
        $where = array(
            array(
                'campo'     => DBT_ID,
                'valore'    => $idRuolo,
                'formato'   => 'INT'
            )
        );
        $temp = $this->getRuoli($where);         
        if($temp != null && count($temp) == 1){            
            $result = updateToRuoloC($temp[0]);
        }
        return $result;
    }
    
    /************ ALTRE FUNZIONI ********/
    
    public function createUtenteWP($email, $password, $ruolo, $nome = null, $cognome = null){
        $user_login = $email;
        $user_pass = $password;
        $user_email = $email;
        $idUWp = wp_create_user($user_login, $user_pass, $user_email);
        if (!is_wp_error($idUWp)) {
            $user_id_role = new \WP_User($idUWp);       
            $user_id_role->set_role($ruolo);
            
            //salvo nome e cognome
            if($nome != null && $cognome != null){
                wp_update_user([
                    'ID'            => $idUWp,
                    'first_name'    => $nome,
                    'last_name'     => $cognome
                ]);
            }
            else if($nome != null && $cognome == null){
                //ho solo il nome --> si tratta di Azienda
                wp_update_user([
                    'ID'            => $idUWp,
                    'first_name'    => $nome                    
                ]);
            }
        }        
        return $idUWp;
    }
    
    public function aggiornaUtenteWP($idWP, $nome, $cognome=null){
        //aggiorno nome e cognome nell'utente
        if($cognome != null){
            wp_update_user([
                'ID'            => $idWP,
                'first_name'    => $nome,
                'last_name'     => $cognome
            ]);
        }
        else{
            wp_update_user([
                'ID'            => $idWP,
                'first_name'    => $nome
            ]);
        }
        
        return true;
        
    }
    
    public function updatePasswordUtenteWP($password, $user_id){
        return wp_set_password($password, $user_id);
    }
    
    public function deleteUtenteWP($idUWP){
        wp_delete_user($idUWP);
    }
    
    
    public function getRuoliPerSuggerimenti(){
        $result = array();
        $temp = $this->rDAO->getResults();
        if(checkResult($temp)){
            foreach($temp as $item){
                $obj = updateToRuoloC($item);
                array_push($result, $obj->getNome());
            }
        }
        return $result;
    }
    
    
    
    
    public function delete($ID) {
        
    }

    public function save(MyObject $o) {
        
    }

    public function update(MyObject $o) {
        
    }
    
    
    /**
     * La funzione restituisce gli ID di ruoli passato il nome di un ruolo
     * Può restituire da zero a n id in quanto la query avviene sulla ricerca di una stringa all'interno del nome e non del nome esatto
     * @param type $nome
     * @return array
     */
    private function getRuoliByNome($nome){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_NOME,
                'valore'    => $nome,
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            )
        );
        $temp = $this->rDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $r = updateToRuoloC($item);
                array_push($result, $r->getID());
            }
        }
        
        return $result;
    }

    /**
     * Restituisce un array di idUtenteC conoscendo l'id Collaudo e il nome del ruolo
     * @param type $nomeRuolo
     * @param type $idCollaudo
     * @return array
     */
    public function getUtenteCByRuoloCollaudo($nomeRuolo, $idCollaudo){
        //devo ottenere gli id di utenteC
        $result = array();
        //da un nome ruolo, ottengo l'array idRuolo
        $ids = $this->getRuoliByNome($nomeRuolo);
        if(checkResult($ids)){
            //continuo se ho dei match, altrimenti no
            foreach($ids as $id){
                $where = array(
                    array(
                        'campo'     => DBT_IDCOLLAUDO,
                        'valore'    => $idCollaudo,
                        'formato'   => 'INT'
                    ),
                    array(
                        'campo'     => DBT_IDRC,
                        'valore'    => $id,
                        'formato'   => 'INT'
                    )
                );
                //dovrebbe restituire un valore solo
                $temp = $this->urDAO->getUtentiRuoli($where);                
                if($temp != null && count($temp) == 1){
                    $ur = updateToUteRuo($temp[0]);
                    array_push($result, $ur->getIdUtenteC());
                }
            }
        }
        
        return $result;
    }
    
    public function getIdUtenteCByIdRuoloCollaudo($idRuolo, $idCollaudo){
        $result = null;
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            ),
            array(
                'campo'     => DBT_IDRC,
                'valore'    => $idRuolo,
                'formato'   => 'INT'
            )
        );
        $temp = $this->urDAO->getUtentiRuoli($where);
        //dovrebbe restituire un valore solo
        $temp = $this->urDAO->getUtentiRuoli($where);                
        if($temp != null && count($temp) == 1){
            $ur = updateToUteRuo($temp[0]);
            $result = $ur->getIdUtenteC();
        }
        return $result;
    }
    
    /**
     * La funzione mi restituisce un array di email degli utenti di un determinato collaudo che hanno un determinato ruolo
     * @param type $nomeRuolo
     * @param type $idCollaudo
     * @return array
     */
    public function getEmailsByRuoloCollaudo($nomeRuolo, $idCollaudo){
        //devo ottenere le email
        $result = array();
        //ottengo gli id degli utenti
        $ids = $this->getUtenteCByRuoloCollaudo($nomeRuolo, $idCollaudo);
        if(checkResult($ids)){
            foreach($ids as $id){
                //ottengo l'utente
                $utenteC = updateToUtenteC($this->getUtenteCById($id));
                array_push($result, $utenteC->getEmail());
            }
        }        
        return $result;
    }
    
    public function getEmailByIdUtenteC($idUtenteC){
        $u = updateToUtenteC($this->getUtenteCById($idUtenteC));
        return $u->getEmail();
    }
    
    
    public function getNomeRuoliUtente($idCollaudo){
        $result = array();
        $idUtenteC = getIdUtenteCByUtenteWP(get_current_user_id());
        if($idUtenteC != null){
            $where = array(
                array(
                    'campo'     => DBT_IDCOLLAUDO,
                    'valore'    => $idCollaudo,
                    'formato'   => 'INT'
                ),
                array(
                    'campo'     => DBT_IDUTENTEC,
                    'valore'    => $idUtenteC,
                    'formato'   => 'INT'
                )
            );
            $temp = $this->urDAO->getUtentiRuoli($where);

            if(checkResult($temp)){
                $ruoli = array();
                foreach($temp as $item){
                    $ur = updateToUteRuo($item);
                    array_push($ruoli, $ur->getIdRuoloC());                
                }
                foreach($ruoli as $item2){
                    $ruolo = updateToRuoloC($this->rDAO->getResultByID($item2));               
                    array_push($result, $ruolo->getNome());
                }            
            }
        }
        
        return $result;
    }
}
