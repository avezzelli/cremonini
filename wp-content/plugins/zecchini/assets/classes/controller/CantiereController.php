<?php

namespace zecchini;

class CantiereController implements InterfaceController {
    private $DAO;
    private $azienda;
    private $collaudo; 
    private $acDAO;
    
    function __construct() {
        $this->DAO = new CantiereDAO();
        $this->azienda = new AziendaController();
        $this->collaudo = new CollaudoController(); 
        $this->acDAO = new AziendaCantiereDAO();
    }

    /************** CANTIERE **********************/
    
    public function delete($ID) {        
        //rimuovo i collaudi
        $this->collaudo->deleteCollaudiByCantiere($ID);        
        return $this->DAO->deleteByID($ID);
    }

    public function save(MyObject $o) {    
        $cantiere = updateToCantiere($o);
        
        //il salvataggio del cantiere avviene in n passaggi:
        //1. salvo il cantiere e ottengo l'id
                
        $idCantiere = $this->DAO->save($cantiere);
        
        
        if($idCantiere > 0){
            //2. associo il cantiere alle aziende popolando aziendaCantiere
            $this->saveAziendeCantiere($cantiere->getAziende(), $idCantiere);
                        
            //3. ottengo un oggetto precollaudo dall'Id passato e lo salvo facendone una copia
            $temp1 = $this->collaudo->getCollaudoByID($cantiere->getPrecollaudo());
            if($temp1 != null){
                $precollaudo = updateToCollaudo($temp1); 
                $precollaudo->setStato(COLLAUDO_STATO_INCORSO);
                $this->collaudo->saveCollaudoInCantiere($precollaudo, $idCantiere);
            }
            
            //4. ottengo un oggetto collaudo dall'Id passato e lo salvo facendone una copia
            $temp2 = $this->collaudo->getCollaudoByID($cantiere->getCollaudo());
            if($temp2 != null){
                $collaudo = updateToCollaudo($temp2);
                $collaudo->setStato(COLLAUDO_STATO_DAINIZIARE);
                $this->collaudo->saveCollaudoInCantiere($collaudo, $idCantiere);
            }            
            
            return $idCantiere;
        }
        return -1;
    }
    
    private function saveAziendeCantiere($array, $idCantiere){
        if(checkResult($array)){
            foreach($array as $item){
                $ac = new AziendaCantiere();
                $ac->setIdCantiere($idCantiere);
                $ac->setIdAzienda($item);
                if(!$this->acDAO->save($ac)){
                    return -2;
                }
            }
        }
        return true;
    }

    public function update(MyObject $o) {
        //aggiorno il cantiere
        $c = updateToCantiere($o);        
        $update = $this->DAO->update($c);        
        //devo aggiornare anche azienda/cantiere
        //cancello aziende cantiere
        $this->acDAO->deleteObject(array(DBT_IDCANTIERE => $c->getID()));
        //salvo nuovamente
        $this->saveAziendeCantiere($c->getAziende(), $c->getID());
        
        return $update;
        
    }    
    
    /**
     * Ottengo un cantiere
     * @param type $idCantiere
     * @return type
     */
    public function getCantiere($idCantiere){
        $temp = $this->DAO->getResultByID($idCantiere);
        if($temp != null){        
            $cantiere = updateToCantiere($temp);
            $cantiere->setAziende($this->getAziende($idCantiere));
            //carico il precollado
            $cantiere->setPrecollaudo($this->collaudo->getObjCollaudo(TIPO_PRECOLLAUDO, $idCantiere));
            //carico il collaudo
            $cantiere->setCollaudo($this->collaudo->getObjCollaudo(TIPO_COLLAUDO, $idCantiere));
            //Carico i ruoli

            return $cantiere;
        }
        return null;
    }
    
    /**
     * Funzione che restituisce i cantieri comprensivi di aziende --> Mi serve per le ricerche del brand
     * @param type $where
     * @return array
     */
    public function getCantieri($where){
        $result = array();
        $temp = $this->DAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $c = updateToCantiere($item);
                array_push($result, $this->getCantiere($c->getID()));
            }
        }
        return $result;
    }
            
        
    /************** COLLAUDO **********************/
    public function associaCollaudo($idCantiere, $idCollaudo){
        //La funzione associa un collaudo al cantiere
        //Per farlo, individua un collaudo e lo copia salvandolo con l'id cantiere associato
        
        //ottengo l'oggetto collaudo
        $c = updateToCollaudo($this->collaudo->getCollaudoByID($idCollaudo));
        //salvo il collaudo nuovo nel cantiere
        return $this->collaudo->saveCollaudo($c, $idCantiere);
    }
    
    public function rimuoviCollaudo($idCollaudo){
        return $this->collaudo->deleteCollaudo($idCollaudo);
    }
    
    
    /**************** AZIENDA **********************/
    public function associaCantiereAzienda($idCantiere, $idAzienda){
        $obj = new AziendaCantiere();
        $obj->setIdAzienda($idAzienda);
        $obj->setIdCantiere($idCantiere);
        return $this->acDAO->save($obj);
    }
    
    public function rimuoviCantiereAzienda($idAzienda, $idCantiere){
        return $this->acDAO->deleteObject(array(DBT_IDCANTIERE => $idCantiere, DBT_IDAZIENDA => $idAzienda));
    }
    
    /**
     * Ottengo un array di aziende dato il cantiere
     * @param type $idCantiere
     * @return array
     */
    public function getAziende($idCantiere){   
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDCANTIERE,
                'valore'    => $idCantiere,
                'formato'   => 'INT'
            )
        );
        $temp = $this->acDAO->getAziendeCantieri($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $obj = updateToAziCan($item);
                array_push($result, $obj->getIdAzienda());
            }
        }
        return $result;
    }

    /**************** AZIENDA / CANTIERE **********************/
    
    public function getCantiereByIdAzienda($idAzienda){
        //posso avere anche più di un cantiere associato all'azienda
        
    }
    
}
