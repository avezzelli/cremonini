<?php

namespace zecchini;

class CollaudoController implements InterfaceController {
    
    private $collaudoDAO;
    private $gvDAO;
    private $voceDAO;
    private $commentoDAO;
    private $visibilitaDAO;
    private $colruoDAO;
    private $urDAO;
    private $logDAO;
    private $respDAO;   
    
    function __construct() {
        $this->collaudoDAO = new CollaudoDAO();
        $this->gvDAO = new GruppoVoceDAO();
        $this->voceDAO = new VoceDAO();
        $this->commentoDAO = new CommentoDAO();
        $this->visibilitaDAO = new VisibilitaDAO();
        $this->colruoDAO = new CollaudoRuolocDAO();
        $this->urDAO = new UtentecRuolocDAO();
        $this->logDAO = new LogDAO();
        $this->respDAO = new ResponsabileCollaudoDAO();
    }

    public function delete($ID) {
        
    }

    public function save(MyObject $o) {
        
    }

    public function update(MyObject $o) {
        
    }
    
    /***********************************************************************/
    /***************************** COLLAUDO ********************************/
    /***********************************************************************/
    
    public function deleteCollaudo($ID){
        //cancello il collaudo, devo cancellare anche tutto il resto che lo compone:
        
        //cancello i gruppi voce
        $this->deleteGruppoVociByCollaudo($ID);
        //cancello i responsabili
        $this->deleteResponsabiliByIdCollaudo($ID);
        //cancello i ruoli associati al collaudo
        $this->deleteCollaudoRuoloByCollaudo($ID);
        //cancello gli utenti collaudo
        $this->deleteUtenteRuoliCByIdCollaudo($ID);
        
        return $this->collaudoDAO->deleteByID($ID);
    }
    
    /**
     * Elimino tutti i collaudi di un cantiere
     * @param type $idCantiere
     * @return boolean
     */
    public function deleteCollaudiByCantiere($idCantiere){
        $where = array(
            array(
                'campo'     => DBT_IDCANTIERE,
                'valore'    => $idCantiere,
                'formato'   => 'INT'
            )
        );
        $temp = $this->collaudoDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $collaudo = updateToCollaudo($item);
                if(!$this->deleteCollaudo($collaudo->getID())){
                    return false;
                }
            }
        }
        return true;
    }
    
    public function saveCollaudo(Collaudo $obj){         
        return $this->collaudoDAO->save($obj);
    }
    
    public function saveCollaudoInCantiere(Collaudo $obj, $idCantiere){
        $obj->setIdCantiere($idCantiere);
        //devo salvare il collaudo e tutto il gruppo voci
        $idCollaudo = $this->collaudoDAO->save($obj);
        
        //salvo i ruoli
        $ruoli = $obj->getRuoli();
        foreach($ruoli as $item){
            $ruolo = updateToRuoloC($item);
            $colRuo = new CollaudoRuoloc();
            $colRuo->setIdCollaudo($idCollaudo);
            $colRuo->setIdRuoloC($ruolo->getID());
            //salvo l'associazione
            $this->saveCollaudoRuoloC($colRuo);
        }

        //salvo i gruppi voci se si tratta solo di un precollaudo
        //da modifica, se si tratta di un collaudo, questo dovrà avere i campi copiati dal precollaudo
        //quindi in questa fase il collaudo può non avere i gruppi voce che saranno generati in un secondo momento.
        if($obj->getTipo() == TIPO_PRECOLLAUDO){
            //salvo i gruppi voce
            if(checkResult($obj->getGruppoVoci())){
                foreach($obj->getGruppoVoci() as $item){
                    $gv = updateToGV($item);
                    $gv->setIdCollaudo($idCollaudo);
                    if(!$this->saveGruppoVoce($gv) > 0){
                        return false;
                    }
                }            
            }       
        }
        return true;        
    }
    
    public function updateCollaudo(Collaudo $obj){
        return $this->collaudoDAO->update($obj);
    }
    
    public function updateCollaudoInCantiere(Collaudo $obj){
        //questa funzione svolge due azioni:
        //1. Aggiorno la tabella collaudo
        $update = $this->collaudoDAO->update($obj);
        //2. Elimno dalla tabella utentec/ruoloc le occorrenze relative al collaudo 
        $this->urDAO->deleteObject(array(DBT_IDCOLLAUDO => $obj->getID()));
        //3. Salvo le nuove associazioni del collaudo
        return $this->saveUtenteRuolic($obj->getRuoli(), $obj->getID());
    }
    
    private function saveUtenteRuolic($array, $idCollaudo){        
        if(checkResult($array)){
            foreach($array as $item){
                $ur = updateToUteRuo($item);               
                if(!$this->urDAO->save($ur)){
                    return false;
                }
            }
        }
        return true;
    }
    
    public function deleteUtenteRuoliCByIdCollaudo($idCollaudo){
        return $this->urDAO->deleteObject(array(DBT_IDCOLLAUDO => $idCollaudo));
    }
    
    
    
    public function getModelliCollaudo(){
        //devo ottenere tutta la struttura dei collaudi che hanno come 
        //id_cantiere = 0
        
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDCANTIERE,
                'valore'    => 0,
                'formato'   => 'INT'
            )
        );
        $temp = $this->collaudoDAO->getResults($where);        
        if(checkResult($temp)){
            foreach($temp as $item){
                $c = updateToCollaudo($item);
                $collaudo = $this->getCollaudoByID($c->getID()); 
                array_push($result, $collaudo);
            }
        }
        
        return $result;
    }
    
    
    public function getCollaudoByID($idCollaudo){        
        //la funzione va a prendere tutta la struttura del collaudo conoscendone l'id        
        $temp = $this->collaudoDAO->getResultByID($idCollaudo);
        if($temp != null){
            $collaudo = updateToCollaudo($temp);

            //ottengo i responsabili assegnati al collaudo
            $collaudo->setResponsabili($this->getResponsabiliByIdCollaudo($collaudo->getID()));
            
            //ottengo i ruoli assegnati al collaudo
            $tempRuoli = getRuoliByIdCollaudo($idCollaudo);
            $ruoli = array();
            if(checkResult($tempRuoli)){
                foreach($tempRuoli as $item){
                    $cr = updateToColRuo($item);
                    $temp = getRuoloByID($cr->getIdRuoloC());
                    array_push($ruoli,$temp);
                }
            }        
            $collaudo->setRuoli($ruoli);       
            //ottengo i gruppi voci
            $collaudo->setGruppoVoci($this->getGruppoVociByIdCollaudo($collaudo->getID()));
            return $collaudo;
        }
        return null;        
    }
    
    public function getCollaudi($tipo){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDCANTIERE,
                'valore'    => 0,
                'formato'   => 'INT'
            ),
            array(
                'campo'     => DBT_COLLAUDO_TIPO,
                'valore'    => $tipo,
                'formato'   => 'INT'
            ),
        );
        $temp = $this->collaudoDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $c = updateToCollaudo($item);
                $result[$c->getID()] = $c->getNome();
            }
        }
        return $result;
        
    }
    
    public function getObjCollaudo($tipo, $idCantiere){
        $where = array(
            array(
                'campo'     => DBT_IDCANTIERE,
                'valore'    => $idCantiere,
                'formato'   => 'INT'
            ),
            array(
                'campo'     => DBT_COLLAUDO_TIPO,
                'valore'    => $tipo,
                'formato'   => 'INT'
            ),
        );
        $temp = $this->collaudoDAO->getResults($where);
        //il collaudo di un determinato tipo associato al cantiere corrisponde ad una unità
        if($temp != null && count($temp) == 1){
            $c = updateToCollaudo($temp[0]);
            return $this->getCollaudoByID($c->getID());
        }
       
    }
    
    
    public function generaPDF(Collaudo $collaudo){
        
        
        $file_url = '';
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $options->set('defaultFont', 'Arial');
        
        $upload_dir = wp_upload_dir();
        
        if($collaudo != null){
            
            //GENERO IL PDF
            $dompdf = new \Dompdf\Dompdf();
            
            $style = '<style type="text/css">';
            $style .= 'body{ font-family: \'Helvetica\' !important; padding:25px }'; 
            $style .= 'footer {position: fixed; bottom: 0; width:100%; text-align:right; height: 10px; font-size:14px} footer .pagenum:before { content: counter(page); font-weight:100 }';
            $style .='</style>';
            
            $html = '<!doctype html><html lang="it"><head>'.$style.'</head><body>';
            $html .= $this->printCollaudoPdf($collaudo);
            $html .= '<footer><div class="pagenum-container">Pag. <span class="pagenum"></span></div></footer>';
            $html .= '</body></html>';
        }
        
        $dompdf->loadHtml(stripslashes($html));
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        
        $pdf = $dompdf->output();
        
        $titoloFile = 'collaudo-'.$collaudo->getID().'.pdf';
        $file = $upload_dir['path'] . "/" . $titoloFile;
        $file_url = $upload_dir['url'] . "/" . $titoloFile;
        file_put_contents($file, $pdf);
                
        return $file_url;
    }
    
    private function printCollaudoPdf(Collaudo $c){       
        $html = '';
        $cantiere = getCantiere($c->getIdCantiere());
        
        $brand = getBrand($cantiere->getIdBrand());
        
        //imposto le informazioni del cantiere
        
        $html .= '<div class="prima-pagina" style="page-break-after: always;">';
        
        $html .= '<h3 style="text-align:center; font-family: \'Helvetica\';">INFORMAZIONI CANTIERE</h3>';
        $html .= '<table style="width:100%">';
        $html .= '<tr><td style="width:25%"><strong>BRAND</strong></td><td>'.$brand->getNome().'</td></tr>';
        $html .= '<tr><td style="width:25%"><strong>CANTIERE</strong></td><td>'.$cantiere->getNome().'</td></tr>';
        $html .= '<tr><td><strong>Indirizzo</strong></td><td>'.$cantiere->getIndirizzo().'</td></tr>';
        $html .= '<tr><td><strong>Data apertura</strong></td><td>'.$cantiere->getDataApertura().'</td></tr>';
        $html .= '<tr><td><strong>Data chiusura</strong></td><td>'.$cantiere->getDataChiusura().'</td></tr>';
        $html .= '</table>';
        
        $html .= '<h3 style="text-align:center; font-family:Arial">INFORMAZIONI COLLAUDO</h3>';
        $html .= '<table style="width:100%">';
        
        $tipo = '';
        if($c->getTipo() == TIPO_PRECOLLAUDO){
            $tipo = 'PRECOLLAUDO';
        }
        else if($c->getTipo() == TIPO_COLLAUDO){
            $tipo = 'COLLAUDO';
        }
        
        $html .= '<tr><td><strong>Tipo</strong></td><td>'.$tipo.'</td></tr>';
        $html .= '<tr><td style="width:45%"><strong>Data Collaudo</strong></td><td>'. $c->getDataCollaudo().'</td></tr>';
        
        if(checkResult($c->getRuoli())){            
            foreach($c->getRuoli() as $item){
                $ruoloC = updateToRuoloC($item);
                $html .= '<tr><td style="width:45%"><strong>'.$ruoloC->getNome().'</strong></td><td>'. getNomeResponsabile($ruoloC->getID(), $c->getID()).'</td></tr>';
            }
        }
        
        if($c->getNote() != null && $c->getNote() != ''){
            $html .= '<tr><td colspan="2"><strong>Note</strong></td></tr>';
            $html .= '<tr><td colspan="2">'.$c->getNote().'</td></tr>';
        }
        $html .= '</table>';
                
        $html .= '</div>';
        
        //GRUPPI VOCE
        $countGV = 1;
        foreach($c->getGruppoVoci() as $item){
            $gv = updateToGV($item);
            $html .= '<div class="gruppo-voce" style="page-break-after: always;">';
            $html .= '<div style="margin-bottom:15px;"><strong>'.$countGV.' '.$gv->getTitolo().'</strong></div>';
            
            $countVoce = 1;
            foreach($gv->getVoci() as $item2){
                $voce = updateToVoce($item2);
                $html .= '<div style="page-break-inside: avoid;">';
                $html .= '<table border="1" cellpadding="5" class="voce" style="margin-bottom:25px; width:100%; border-collapse: collapse;">';
                $html .= '<tr><td colspan="2"><strong>'.$countGV.'.'.$countVoce.'</strong> '.$voce->getDescrizione().'</td></tr>';
                if($voce->getEsito() != null){
                    if($voce->getTipo() == VOCE_TIPO_TESTO){
                        $esito = $voce->getEsito();
                    }
                    else if($voce->getTipo() == VOCE_TIPO_SINO){
                        if($voce->getEsito() == 1){
                            $esito = 'NO';
                        }
                        else if($voce->getEsito() == 2){
                            $esito = 'SI';
                        }
                    }
                    $html .= '<tr><td style="width:45%"><strong>Esito</strong></td><td>'.$esito.'</td></tr>';
                }
                $html .= '<tr><td style="width:45%"><strong>Data limite risoluzione</strong></td><td>'.$voce->getDataLimiteRisoluzione().'</td></tr>';
                $html .= '<tr><td style="width:45%"><strong>Data verifica ultimazione</strong></td><td>'.$voce->getDataVerificaUltimazione().'</td></tr>';
                
                if($voce->getNote() != null && $voce->getNote() != ''){                
                    $html .= '<tr><td colspan="2"><strong>Note</strong></td></tr>';
                    $html .= '<tr><td colspan="2">'.$this->updateUrlForPDF($voce->getNote()).'</td></tr>';
                }
                $html .= '<tr><td style="width:45%"><strong>STATO</strong></td><td>'. getStatoVoce($voce->getStato()).'</td></tr>';                
                $html .= '</table>';
                
                $html .= '</div>';
                $countVoce++;
            }                       
            
            $html .= '</div>';
            
            $countGV++;
        }
        
        /*
        print_r($html);
        print_r('<br>---<br>');
        print_r(home_url());
        print_r('<br>---<br>');
        print_r($_SERVER["DOCUMENT_ROOT"]);
        die();
        * */
        
                
        return $html;
    }
            
    private function updateUrlForPDF($url){
        $url = str_replace(home_url(), $_SERVER["DOCUMENT_ROOT"].'zecchini/', $url);
        return $url;
    }
    
    
    /***********************************************************************/
    /***************************** GRUPPO VOCE ********************************/
    /***********************************************************************/
    
    public function deleteGruppoVoce($ID){
        //cancello anche la visibilità
        $this->visibilitaDAO->delete(array(DBT_IDGV => $ID));
        
        //cancellare il gruppo voce elimina anche tutte le voci inserite
        $this->deleteVociByGruppoVoce($ID);
        return $this->gvDAO->deleteByID($ID);
    }
   
    /**
     * Elimina tutti i gruppi voci di un collaudo
     * @param type $idCollaudo
     * @return boolean
     */
    public function deleteGruppoVociByCollaudo($idCollaudo){
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            )
        );
        $temp = $this->gvDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $gv = updateToGV($item);
                $this->deleteGruppoVoce($gv->getID()); 
                //cancello tutti i log relativi al gruppo voce
                $this->deleteLogsByGV($gv->getID());
                //cancello tutte le visibilità
                $this->deleteVisibilitaByGV($gv->getID());
            }
        }
        
        return true;
    }
    
    /**
     * Salvo il gruppo voce e la visibilità dei ruoli
     * @param \zecchini\GruppoVoce $obj
     * @param type $idCollaudo
     * @param type $ruoli
     * @return type
     */
    public function saveGruppoVoce(GruppoVoce $obj){       
        $idGV = $this->gvDAO->save($obj);        
        if($idGV > 0){
            //salvo la visibilità
            if(!$this->saveVisibilita($obj->getVisibilita(), $idGV)){
                return -1;
            }            
            //salvo anche le voci
            if(!$this->saveVoci($obj->getVoci(), $idGV)){
                return -2;
            }            
        }        
        return $idGV;
    }
    
    public function copiaGruppiVoceDaPrecollaudo($gvs, $idCollaudo){
        if(checkResult($gvs)){           
            foreach($gvs as $item){
                $gv = updateToGV($item);
                $gv->setIdCollaudo($idCollaudo);
                //print_r($gv);              
                //salvo il gruppo voce
                $idGV = $this->gvDAO->save($gv);
                if($idGV > 0){
                    //salvo la visibilità                    
                    if(!$this->saveVisibilita($gv->getVisibilita(), $idGV)){
                        //return -1;
                    }                  
                    //salvo le voci
                    if($this->saveVoci($gv->getVoci(), $idGV)){
                        //return -2;
                    }
                }
            }            
        }
        return true;
        
    }
    
    public function updateGruppoVoce(GruppoVoce $obj){
        //L'aggiornamento avviene in tre fasi:
        //1. aggiorno il gruppo voce            
        $update = $this->gvDAO->update($obj);
        //2. aggiorno la visibilità (elimino le visibilità e le re-inserisco)
        $this->visibilitaDAO->delete(array(DBT_IDGV => $obj->getID()));
        //salvo nuovamente
        if(!$this->saveVisibilita($obj->getVisibilita(), $obj->getID())){
            return -1;
        }
        
        //3. salvo le voci (elimino quelle attuali e le risalvo)
        $this->voceDAO->delete(array(DBT_IDGV => $obj->getID()));
        if(!$this->saveVoci($obj->getVoci(), $obj->getID())){
            return -2;
        }
        
        return $update;
    }
    
    /**
     * Salvo le voci nel DB
     * @param type $array
     * @param type $idGV
     * @return boolean
     */
    private function saveVoci($array, $idGV){
        if(checkResult($array)){
            foreach($array as $item){
                $voce = updateToVoce($item);                
                if(!$this->saveVoce($voce, $idGV)){
                    return -2;
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     * Salvataggio visibilità nel db
     * @param type $array
     * @param type $idGV
     * @return boolean
     */
    private function saveVisibilita($array, $idGV){        
        if(checkResult($array)){            
            foreach($array as $item){
                $v = new Visibilita();
                $v->setIdGruppoVoci($idGV);
                $v->setIdRuoloC($item);
                if(!$this->visibilitaDAO->save($v)){
                    return -1;
                }
            }
            return true;
        }
        return false;
    }
        
    public function getGruppoVociByIdCollaudo($idCollaudo){
        $result = array();        
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            )
        );        
        
        $order = array(
            array(
                'campo'     => DBT_ID,
                'ordine'    => 'ASC'
            )
        );
        
        $temp = $this->gvDAO->getResults($where, $order);
        if(checkResult($temp)){
            foreach($temp as $item){
                $gv = updateToGV($item);
                $gv->setVoci($this->getVociByGruppoVoce($gv->getID()));
                //trovo anche la visibilità
                $gv->setVisibilita($this->getVisibilitaByIdGV($gv->getID()));
                array_push($result, $gv);                
            }
        }        
        return $result;        
    }
    
    public function getGruppoVoceByID($idGV){
        return $this->gvDAO->getResultByID($idGV);
    }
        
    /***********************************************************************/
    /***************************** VOCE ********************************/
    /***********************************************************************/
    
    public function deleteVoce($ID){
        //elimino prima i commenti associati alla voce
        $this->deleteCommentiByVoce($ID);
        //elimino la voce
        $this->voceDAO->deleteByID($ID);
    }
    
    /**
     * Elimina tutte le voci di un gruppo voce
     * @param type $idGV
     * @return boolean
     */
    public function deleteVociByGruppoVoce($idGV){
        $where = array(
            array(
                'campo'     => DBT_IDGV,
                'valore'    => $idGV,
                'formato'   => 'INT'
            )
        );
        $temp = $this->voceDAO->getResults($where);        
        if(checkResult($temp)){
            foreach($temp as $item){
                $voce = updateToVoce($item);
                $this->deleteVoce($voce->getID());
            }
        }
        return true;
    }
    
    public function saveVoce(Voce $obj, $idGV){
        $obj->setIdGruppoVoce($idGV);
        //se sto salvando la voce allora pongo lo stato di rosso (questo mi torna comodo nel copiare)
        $obj->setStato(VOCE_STATO_ROSSO);
        $idVoce = $this->voceDAO->save($obj);        
        //devo salvare anche i commenti
        if(checkResult($obj->getCommenti())){
            foreach($obj->getCommenti() as $item){
                $commento = updateToCommento($item);
                $commento->setIdVoce($idVoce);
                //print_r($commento);                
                $this->saveCommento($commento);
            }
        }
        return $idVoce;
    }
    
    public function updateVoce(Voce $obj){
        return $this->voceDAO->update($obj);
    }
    
    private function getVociByGruppoVoce($idGV){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDGV,
                'valore'    => $idGV,
                'formato'   => 'INT'
            )
        );
        $order = array(
            array(
                'campo'     => DBT_ID,
                'ordine'    => 'ASC'
            )
        );
        $temp = $this->voceDAO->getResults($where, $order);
        if(checkResult($temp)){
            foreach($temp as $item){
                $voce = updateToVoce($item);
                $voce->setCommenti($this->getCommentiByVoce($voce->getID()));
                array_push($result, $voce);
            }
        }        
        return $result;
    }
    
    public function getVoceByID($idVoce){
        return $this->voceDAO->getResultByID($idVoce);
    }
    
    /***********************************************************************/
    /***************************** COMMENTO ********************************/
    /***********************************************************************/

    public function deleteCommento($ID){
        return $this->commentoDAO->deleteByID($ID);
    }
    
    /**
     * Elimina tutti i commenti di una voce
     * @param type $idVoce
     * @return type
     */
    public function deleteCommentiByVoce($idVoce){
        return $this->commentoDAO->delete(array(DBT_IDVOCE => $idVoce));
    }
    
    public function saveCommento(Commento $obj){        
        return $this->commentoDAO->save($obj);
    }
    
    public function updateCommento(Commento $obj){
        return $this->commentoDAO->update($obj);
    }
    
    private function getCommentiByVoce($idVoce){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDVOCE,
                'valore'    => $idVoce,
                'formato'   => 'INT'
            )
        );
        $temp = $this->commentoDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $commento = updateToCommento($item);
                array_push($result, $commento);
            }
        }
        return $result;
    }
    
    /***********************************************************************/
    /***************************** RUOLOC ********************************/
    /***********************************************************************/
    
    public function saveCollaudoRuoloC(CollaudoRuoloc $obj){
        return $this->colruoDAO->save($obj);
    }
    
    public function deleteCollaudoRuoloC($array){
        return $this->colruoDAO->deleteObject($array);
    }
    
    public function deleteCollaudoRuoloByCollaudo($idCollaudo){
        return $this->deleteCollaudoRuoloC(array(DBT_IDCOLLAUDO => $idCollaudo));
    }
    
    public function getCollaudiRuoloByIdCollaudo($idCollaudo){
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            )
        );
        return $this->colruoDAO->getCollaudoRuoli($where);
    }
    
    
    /***********************************************************************/
    /***************************** VISIBILITA' ********************************/
    /***********************************************************************/
    
    public function getArrayRuoliInCollaudo($idCollaudo){
        $result = array();
        $temp = $this->getCollaudiRuoloByIdCollaudo($idCollaudo);
        if(checkResult($temp)){
            foreach($temp as $item){
                $cr = updateToColRuo($item);
                //ottengo il ruolo
                $ruolo = updateToRuoloC(getRuoloByID($cr->getIdRuoloC()));
                $result[$ruolo->getID()] = $ruolo->getNome();
            }
        }        
        return $result;
            
    }
    
    private function getVisibilitaByIdGV($idGV){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDGV,
                'valore'    => $idGV,
                'formato'   => 'INT'
            )
        );
        $temp = $this->visibilitaDAO->getResults($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $v = updateToVisibilita($item);
                $r = updateToRuoloC(getRuoloByID($v->getIdRuoloC()));
                array_push($result, $r->getID());                
            }
        }        
        return $result;
    }
    
    public function deleteVisibilitaByGV($idGV){
        return $this->visibilitaDAO->delete(array(DBT_IDGV => $idGV));
    }
    
    
    /***********************************************************************/
    /*****************************   LOG    ********************************/
    /***********************************************************************/
    
    public function saveLog($log){
        return $this->logDAO->save($log);
    }
    
    public function getLogs($where, $offset = null){
        $result = array();
        $order = array( 
            array(
                'campo'     => DBT_LOG_DATA,
                'ordine'    => 'DESC'
            )
        );
        $temp = $this->logDAO->getResults($where, $order, $offset);
        if(checkResult($temp)){
            foreach($temp as $item){
                $log = updateToLog($item);
                array_push($result, $log);
            }
        }        
        return $result;        
    }
    
    public function deleteLogs($array){
        return $this->logDAO->deleteObject($array);
    }
    
    public function deleteLogsByGV($idGV){
        return $this->deleteLogs(array(DBT_IDGV => $idGV));
    }
    
    /***********************************************************************/
    /************************* RESPONSABILI COLLAUDO *************************/
    /***********************************************************************/
    
    public function salvaResponsabili($idCollaudo, $arrayResponsabili){
        //la funzione elimina dal database i responsabili e poi li ri-aggiunge
        $this->respDAO->deleteObject(array(DBT_IDCOLLAUDO => $idCollaudo));
        
        //salvo i nuovi responsabili
        if(checkResult($arrayResponsabili)){
            foreach($arrayResponsabili as $item){
                $resp = new ResponsabileCollaudo();
                $resp->setIdCollaudo($idCollaudo);
                $resp->setIdUtenteC($item);
                if(!$this->respDAO->save($resp)){
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function getResponsabiliByIdCollaudo($idCollaudo){
        $result = array();
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            )
        );
        $temp = $this->respDAO->getResponsabiliCollaudo($where);
        if(checkResult($temp)){
            foreach($temp as $item){
                $resp = updateToRespCollaudo($item);
                array_push($result, $resp->getIdUtenteC());
            }
        }
        return $result;
    }
    
    public function deleteResponsabiliByIdCollaudo($idCollaudo){
        return $this->respDAO->deleteObject(array(DBT_IDCOLLAUDO => $idCollaudo));
    }
}
