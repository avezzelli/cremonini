<?php

namespace zecchini;

class AziendaController implements InterfaceController {
    private $DAO;
    private $utente;
    
    function __construct() {
        $this->DAO = new AziendaDAO();
        $this->utente = new UtenteController();
    }

    /**
     * Cancello un'azienda dal DB
     * @param type $ID
     * @return type
     */
    public function delete($ID) {
        //attenzione ad eliminare un'azienda con già degli utenti associati
        if($this->isUtenteInAzienda($ID)){
            return -1;
        }
        
        return $this->DAO->deleteByID($ID);
    }
    
    public function deleteAziendaAndUtenti($ID){
        //Forzo la cancellazione degli utenti
        if($this->utente->deleteAllUtentiByAzienda($ID)){
            return $this->DAO->deleteByID($ID);
        }
        
    }

    public function save(MyObject $o) {
        $obj = updateToAzienda($o);
        return $this->DAO->save($obj);
    }

    public function update(MyObject $o) {
        $obj = updateToAzienda($o);
        return $this->DAO->update($obj);
    }
    
    public function getAllAziende(){
        return $this->DAO->getResults();
    }
    
    public function getAziendeForForm(){
        $result = array();
        $temp = $this->getAllAziende();
        if(checkResult($temp)){
            foreach($temp as $item){
                $azienda = updateToAzienda($item);
                $result[$azienda->getID()] = $azienda->getRagioneSociale();
            }
        }
        return $result;
    }
    
    /**
     * Selezionando un azienda da un ID noto, associo anche tutti gli utenti associati ad essa
     * @param type $ID
     */
    public function getAziendaByID($ID){
        $temp = $this->DAO->getResultByID($ID);   
        $azienda = null;
        if($temp != null){
            $azienda = updateToAzienda($temp);
            $azienda->setUtentiC($this->getUtentiByAzienda($ID));
        }
        return $azienda;
    }
    
    public function search($array, $offset = null){
        //ho 3 campi da controllare: ragione sociale, partita iva, referente
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_AZIENDA_RSOCIALE,
                'valore'    => $array[FRM_AZIENDA_RAGIONES],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            ),
            array(
                'campo'     => DBT_AZIENDA_PIVA,
                'valore'    => $array[FRM_AZIENDA_PIVA],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            ),
            array(
                'campo'     => DBT_AZIENDA_REFERENTE,
                'valore'    => $array[FRM_AZIENDA_REFERENTE],
                'formato'   => 'STRING',
                'operatore' => 'LIKE'
            )
        );
        $temp = $this->DAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $azienda = updateToAzienda($item);
                array_push($result, $azienda);
            }
        }
        return $result;
    }
    
       
        
    /********************************* UTENTI CANTIERE ***************************/
    
    /**
     * La funzione controlla se un utente è inserito in un'azienda
     * @param type $idAzienda
     * @return boolean
     */
    private function isUtenteInAzienda($idAzienda){
        $result = false;
        $where = array(
            array(
                'campo'     => DBT_IDAZIENDA,
                'valore'    => $idAzienda,
                'formato'   => 'INT'
            )
        );
        
        $temp = $this->utente->getUtentiC($where);
        if(checkResult($temp)){
            $result = true;
        }
        
        return $result;
    }
    
    public function getUtentiByAzienda($idAzienda){
        $where = array(
            array(
                'campo'     => DBT_IDAZIENDA,
                'valore'    => $idAzienda,
                'formato'   => 'INT'
            )
        );
        return $this->utente->getUtentiC($where);        
    }
    
    /**
     * Restituisce l'id azienda conoscendo l'utente
     * @param type $idUC
     * @return type
     */
    public function getAziendaByUtenteC($idUC){        
        $temp = $this->utente->getUtenteCById($idUC);
        if($temp != null){
            $utentec = updateToUtenteC($temp);
            return $utentec->getIdAzienda();
        }
        return null;
    }
    
    public function getCantieristiForForm($array){
        $result = array();
        
        //aggiungo prima i responsabili del cliente (quelli con id_azienda = 0)
        $responsabiliCliente = $this->utente->getResponsabiliCliente();
        
        if(checkResult($responsabiliCliente)){
            foreach($responsabiliCliente as $item){
                $r = updateToUtenteC($item);
                $result[$r->getID()] = $r->getCognome().' '.$r->getNome();
            }
        }
        
        if(checkResult($array)){
            foreach($array as $item){
                $azienda = $this->getAziendaByID($item);
                $where = array(
                    array(
                        'campo'     => DBT_IDAZIENDA,
                        'valore'    => $item,
                        'formato'   => 'INT'
                    )
                );
                $temp = $this->utente->getUtentiC($where);
                if(checkResult($temp)){
                    foreach($temp as $item){
                        $u = updateToUtenteC($item);
                        $result[$u->getID()] = $azienda->getRagioneSociale().' - '. $u->getCognome().' '.$u->getNome();
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getAziendeUtenticForForm($array){
        //devo scoroporare l'array ed ottenere gli id azienda
        //devo creare un unico array con key = azienda-idazienda/utentec-idutentec e value = nome corrispondente
        $result = array();
        //EDIT
        //i primi oggetti dell'array sono i responsabili del proprietario, cioè gli utentic con idazienda = 0
        $responsabili = $this->utente->getResponsabiliCliente();
        if(checkResult($responsabili)){
            foreach($responsabili as $item){
                $r = updateToUtenteC($item);
                $result['utentec-'.$r->getID()] = $r->getCognome().' '.$r->getNome();
            }
        }
        
        
        if(checkResult($array)){
            foreach($array as $item){
                //item è l'id dell'azienda
                //ottengo il nome
                $azienda = $this->getAziendaByID($item);
                $result['azienda-'.$item] = $azienda->getRagioneSociale();
                //ottenuta l'azienda trovo gli utenti
                $where = array(
                    array(
                        'campo'     => DBT_IDAZIENDA,
                        'valore'    => $item,
                        'formato'   => 'INT'
                    )
                );
                $temp = $this->utente->getUtentiC($where);
                if(checkResult($temp)){
                    foreach($temp as $item){
                        $u = updateToUtenteC($item);
                        $result['utentec-'.$u->getID()] = $azienda->getRagioneSociale().' - '. $u->getCognome().' '.$u->getNome();
                    }
                }
            }
        }
        
        return $result;
    }
    
}
