<?php

namespace zecchini;

class CantiereView extends PrinterView implements InterfaceView {
    private $cantiere;
    private $collaudo;
    private $azienda;
    private $statoCantiere;
    private $statoCollaudo;
    
    function __construct() {
        parent::__construct();
        $this->collaudo = new CollaudoController();
        $this->azienda = new AziendaController();
        $this->cantiere = new CantiereController();
        $this->statoCantiere = array(
            CANTIERE_STATO_APERTO => 'Aperto',
            CANTIERE_STATO_CHIUSO => 'Chiuso'
        );
        $this->statoCollaudo = array(
            COLLAUDO_STATO_DAINIZIARE   => 'Da iniziare',
            COLLAUDO_STATO_INCORSO      => 'In corso',
            COLLAUDO_STATO_COMPLETATO   => 'Completato'
        );
    }
    
    public function listenerDetailsForm() {
        $this->listenerDettagliCantiere();
        $this->listenerDettaglioCollaudo();
    }

    public function printDetailsForm($ID) {
        $temp = $this->cantiere->getCantiere($ID);        
        if($temp != null){
            $c = updateToCantiere($temp);
            
            
            $preCollaudo = null;
            $collaudo = null;
            
            
            //ottengo il precollaudo ed il collaudo
            if($c->getPrecollaudo() != null){
                $preCollaudo = updateToCollaudo($c->getPrecollaudo());
            }
            
            if($c->getCollaudo() != null){
                $collaudo = updateToCollaudo($c->getCollaudo());
               
            }
            //var_dump($c->getCollaudo());
           
            //devo ottenere un array con il nome delle aziende e i cantieristi sotto di essi
            $arrayAziendeUtentiC = $this->azienda->getAziendeUtenticForForm($c->getAziende());
            
            //ottengo un array con l'id e il nome dei cantieristi coinvolti
            $arrayCantiersti = $this->azienda->getCantieristiForForm($c->getAziende());
            
            //la struttura è suddivisa in tre parti:            
        
            //1. Visualizzazione e modifica dei dettagli del cantiere
        ?>    
                          
            <div class="panel panel-default">
                <div class="panel-heading">Dettagli cantiere</div>
                <div class="panel-body"></div>
                <?php $this->printDettagliCantiere($c) ?>
            </div>
             

            <?php if($preCollaudo != null): ?>
            <!-- PRECOLLAUDO -->
            <ul id="navPrecollaudo" class="nav nav-tabs" role="tablist"> 
                 <li role="presentation" class="active">
                    <a href="#precollaudo" aria-controls="precollaudo" role="tab" data-toggle="tab">Dettagli Precollaudo</a>
                </li>
                <li role="presentation">
                    <a href="#precollaudoLogs" aria-controls="precollaudoLog" role="tab" data-toggle="tab">Logs</a>
                </li>
            </ul>  
            
            <div class="tab-content container-precollaudo-tab">
                <div role="tabpanel" class="tab-pane active" id="precollaudo">
                    <div class="panel panel-default">
                        <div class="panel-heading">Precollaudo</div>
                        <div class="panel-body"></div>
                        <?php $this->printDettagliCollaudo($preCollaudo, 'precollaudo', $arrayAziendeUtentiC, $arrayCantiersti) ?>                
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="precollaudoLogs">
                   <div class="panel panel-default">
                        <div class="panel-heading">Log Precollaudo</div>
                        <div class="panel-body"></div>
                        <?php $this->printCollaudoLog($preCollaudo->getID()) ?>
                    </div>
                </div>
            </div>
            <!-- FINE PRECOLLAUDO -->
            <?php endif; ?>
            
            <?php if($collaudo != null): ?>
            <!-- COLLAUDO -->
            <ul id="navCollaudo" class="nav nav-tabs" role="tablist"> 
                 <li role="presentation" class="active">
                    <a href="#collaudo" aria-controls="precollaudo" role="tab" data-toggle="tab">Dettagli Collaudo</a>
                </li>
                <li role="presentation">
                    <a href="#collaudoLogs" aria-controls="collaudoLog" role="tab" data-toggle="tab">Logs</a>
                </li>
            </ul> 
            
            <div class="tab-content container-collaudo-tab">
                <div role="tabpanel" class="tab-pane active" id="collaudo">
                    <div class="panel panel-default">
                        <div class="panel-heading">Collaudo</div>
                        <div class="panel-body"></div>
                        <?php $this->printDettagliCollaudo($collaudo, 'collaudo', $arrayAziendeUtentiC, $arrayCantiersti, $preCollaudo->getID()) ?>                
                    </div>    
                </div>
                <div role="tabpanel" class="tab-pane" id="collaudoLogs">
                   <div class="panel panel-default">
                        <div class="panel-heading">Log Collaudo</div>
                        <div class="panel-body"></div>
                        
                    </div>
                </div>
            </div>
            <!-- FINE COLLAUDO -->
            <?php endif; ?>
            
        <?php
            //2. Visualizzazione e modifica dei dettagli + ruoli del precollaudo --> bottone che porta ai dettagli + bottone che porta alla bacheca

            //3. Visualizzazione e modifica dei dettagli + ruoli del collaudo --> bottone che porta ai dettagli + bottone che porta alla bacheca
            
        }
        else{
            echo '<p>Cantiere non presente nel sistema</p>';
        }
        
    }
    
    public function listenerSaveForm() {
        if(isset($_POST[FRM_SAVE.FRM_CANTIERE])){            
            $temp = $this->checkFields();            
            if($temp == null){
                return null;
            }
            $save = $this->cantiere->save($temp);
            $this->printMessaggeAfterSave(LBL_CANTIERE, $save);
        }
    }

    public function printSaveForm() {
        parent::printStartAddForm(FRM_CANTIERE);
            //identificativo
            parent::printTextFormField(FRM_CANTIERE_NOME, LBL_CANTIERE_NOME, true);
            //indirizzo
            parent::printTextAreaFormField(FRM_CANTIERE_INDIRIZZO, LBL_CANTIERE_INDIRIZZO);
            //brand
            parent::printSelectFormField(FRM_CANTIERE_BRAND, LBL_BRAND, getBrandsForSelectBox(), true);
            //aziende
            parent::printMultiSelectAdvancedFormField(FRM_CANTIERE_AZIENDE, LBL_CANTIERE_AZIENDE, $this->azienda->getAziendeForForm(), true);
            //precollaudo
            parent::printSelectFormField(FRM_CANTIERE_PRECOLLAUDO, LBL_CANTIERE_PRECOLLAUDO, $this->collaudo->getCollaudi(TIPO_PRECOLLAUDO), true);
            //collaudo
            parent::printSelectFormField(FRM_CANTIERE_COLLAUDO, LBL_COLLAUDO, $this->collaudo->getCollaudi(TIPO_COLLAUDO), true);            
            //data apertura cantiere
            parent::printDatePickerFormField(FRM_CANTIERE_DAPERTURA, LBL_CANTIERE_DAPERTURA, true);
            
        parent::printEndAddForm(FRM_CANTIERE);
    }
    
    protected function checkFields(){
        $errors = 0;
        $cantiere = new Cantiere();
        
        //ID 
        if(isset($_POST[DBT_ID])){
            $cantiere->setID($_POST[DBT_ID]);
            //ottengo gli altri valori
        } 
        else{
            //vuol dire che sto effettuando un salvataggio
            //imposto lo stato del cantiere su APERTO
            $cantiere->setStato(CANTIERE_STATO_APERTO);
        }
        //nome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CANTIERE_NOME, LBL_CANTIERE_NOME) !== false){
            $cantiere->setNome(parent::checkRequiredSingleField(FRM_CANTIERE_NOME, LBL_CANTIERE_NOME));
        }
        else{
            $errors++;
        }        
        //indirizzo - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CANTIERE_INDIRIZZO, LBL_CANTIERE_INDIRIZZO) !== false){
            $cantiere->setIndirizzo(parent::checkRequiredSingleField(FRM_CANTIERE_INDIRIZZO, LBL_CANTIERE_INDIRIZZO));
        }
        else{
            $errors++;
        }        
        //brand - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CANTIERE_BRAND, LBL_CANTIERE_BRAND) !== false){
            $cantiere->setIdBrand(parent::checkRequiredSingleField(FRM_CANTIERE_BRAND, LBL_CANTIERE_BRAND));
        }
        else{
            $errors++;
        }   
        //aziende - obbligatorio
        if(parent::checkMultipleSelectField(FRM_CANTIERE_AZIENDE) !== false){
            $cantiere->setAziende(parent::checkMultipleSelectField(FRM_CANTIERE_AZIENDE));
        }
        else{
            $errors++;
        }
        //precollaudo
        if(parent::checkSingleField(FRM_CANTIERE_PRECOLLAUDO) !== false){
            $cantiere->setPrecollaudo(parent::checkSingleField(FRM_CANTIERE_PRECOLLAUDO));
        }
        
        //collaudo 
        if(parent::checkSingleField(FRM_CANTIERE_COLLAUDO) !== false){
            $cantiere->setCollaudo(parent::checkSingleField(FRM_CANTIERE_COLLAUDO));
        }
       
        //stato - obbligatorio
        if(parent::checkSingleField(FRM_CANTIERE_STATO) !== false){
            $cantiere->setStato(parent::checkSingleField(FRM_CANTIERE_STATO));
        }
        
        //data apertura - obbligatorio
        if(parent::checkRequiredSingleField(FRM_CANTIERE_DAPERTURA, LBL_CANTIERE_DAPERTURA) !== false){
            $cantiere->setDataApertura(parent::checkRequiredSingleField(FRM_CANTIERE_DAPERTURA, LBL_CANTIERE_DAPERTURA));
        }
        else{
            $errors++;
        }
        //data chiusura 
        if(parent::checkSingleField(FRM_CANTIERE_DCHIUSURA) !== false){
            $cantiere->setDataChiusura(parent::checkSingleField(FRM_CANTIERE_DCHIUSURA));
        }
        
        if($errors > 0){
            return null;
        }
        return $cantiere;        
    }
    
    
    public function printCantieriList($idBrand){
        //stampo la lista dei cantieri 
        $where = array(
            array(
                'campo'     => DBT_IDBRAND,
                'valore'    => $idBrand,
                'formato'   => 'INT'
            ), 
            array(
                'campo'     => DBT_STATO,
                'valore'    => CANTIERE_STATO_APERTO,
                'formato'   => 'INT'
            ), 
        );
        $brands = $this->cantiere->getCantieri($where);        
    ?>    
        <div role="tabpanel" class="tab-pane" id="cantieri">
            <h4>Lista cantieri aperti</h4>
            <?php $this->printCantiereTableResult($brands); ?> 
        </div>
    <?php       
    }
    
    
    public function printCantieriChiusiList($idBrand){
        //stampo la lista dei cantieri 
        $where = array(
            array(
                'campo'     => DBT_IDBRAND,
                'valore'    => $idBrand,
                'formato'   => 'INT'
            ), 
            array(
                'campo'     => DBT_STATO,
                'valore'    => CANTIERE_STATO_CHIUSO,
                'formato'   => 'INT'
            ), 
        );
        $brands = $this->cantiere->getCantieri($where);        
    ?>    
        <div role="tabpanel" class="tab-pane" id="cantieri-chiusi">
            <h4>Lista cantieri chiusi</h4>
            <?php $this->printCantiereTableResult($brands); ?> 
        </div>
    <?php 
    }
    
    protected function printCantiereTableResult($array){
        if(checkResult($array)){
            $header = array(LBL_NOME, LBL_CANTIERE_INDIRIZZO, LBL_STATO, 'AZIONI');
            $rows = array();
            $statoCantiere = $this->statoCantiere;
            
            foreach($array as $item){
                $cantiere = updateToCantiere($item);
                $colonne = array(
                    $cantiere->getNome(),
                    $cantiere->getIndirizzo(),
                    $statoCantiere[$cantiere->getStato()],
                    parent::getDetailsButton('dettaglio-'.FRM_CANTIERE, $cantiere->getID())
                );
                array_push($rows, $colonne);
            }
            parent::printTableHover($header, $rows);
        }
        else{
            parent::printNoResults(LBL_CANTIERE);
        }
    }
    
    protected function listenerDettagliCantiere(){
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_CANTIERE])){           
            $temp = $this->checkFields();
            if($temp == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $c = updateToCantiere($temp);
            $update = $this->cantiere->update($c);
            parent::printMessageAfterUpdate($update);
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_CANTIERE])){
            $delete = $this->cantiere->delete($_GET['ID']);
            parent::printMessageAfterDelete($delete);            
        }
    }
    
    protected function printDettagliCantiere(Cantiere $obj){        
        parent::printStartDetailsForm(FRM_CANTIERE);
            parent::printHiddenFormField(DBT_ID, $obj->getID());
            parent::printHiddenFormField(FRM_CANTIERE_BRAND, $obj->getIdBrand());
            parent::printTextFormField(FRM_CANTIERE_NOME, LBL_NOME, true, $obj->getNome());
            parent::printTextAreaFormField(FRM_CANTIERE_INDIRIZZO, LBL_CANTIERE_INDIRIZZO, true, $obj->getIndirizzo());
            parent::printMultiSelectAdvancedFormField(FRM_CANTIERE_AZIENDE, LBL_AZIENDA, $this->azienda->getAziendeForForm(), true, $obj->getAziende());
            parent::printSelectFormField(FRM_CANTIERE_STATO, LBL_STATO, $this->statoCantiere, true, $obj->getStato());
            parent::printDatePickerFormField(FRM_CANTIERE_DAPERTURA, LBL_CANTIERE_DAPERTURA, true, $obj->getDataApertura());
            parent::printDatePickerFormField(FRM_CANTIERE_DCHIUSURA, LBL_CANTIERE_DCHIUSURA, false, $obj->getDataChiusura());  
        parent::printEndDetailsForm(FRM_CANTIERE);
        
    }  
    
    protected function listenerDettaglioCollaudo(){
        if(isset($_POST[FRM_UPDATE.'precollaudo'])){            
            //devo sistemare le voci di collaudo e salvare le associazioni sui ruoli
            $temp = $this->checkCollaudoFields('precollaudo');
                        
            if($temp == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $c = updateToCollaudo($temp);
            $update = $this->collaudo->updateCollaudoInCantiere($c);
            
            //salvo i responsabili
            $this->collaudo->salvaResponsabili($c->getID(), $c->getResponsabili());            
            
            //controllo se il collaudo è nello stato di completato, in questo caso genero anche il pdf in automatico
            if($c->getStato() == COLLAUDO_STATO_COMPLETATO){
                generaPDF2($c->getID());
            }
                        
            parent::printMessageAfterUpdate($update);
        }
        
        if(isset($_POST[FRM_UPDATE.'collaudo'])){
            //devo sistemare le voci di collaudo e salvare le associazioni sui ruoli
            $temp = $this->checkCollaudoFields('collaudo');
            if($temp == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $c = updateToCollaudo($temp);
            $update = $this->collaudo->updateCollaudoInCantiere($c);
            
            //salvo i responsabili
            $this->collaudo->salvaResponsabili($c->getID(), $c->getResponsabili());  
            
            parent::printMessageAfterUpdate($update);
        }
    }
    
    protected function printDettagliCollaudo(Collaudo $obj, $tipo, $arrayForm, $arrayCantieristi, $idPreCollaudo = null){
        parent::printStartDetailsForm($tipo);
            //id collaudo
            parent::printHiddenFormField($tipo.'-'.DBT_IDCOLLAUDO, $obj->getID());
            //id cantiere
            parent::printHiddenFormField($tipo.'-'.DBT_IDCANTIERE, $obj->getIdCantiere());
            //tipo cantiere
            parent::printHiddenFormField($tipo.'-'.FRM_COLLAUDO_TIPO, $obj->getTipo());
            //Nome
            parent::printTextFormField($tipo.'-'.FRM_COLLAUDO_NOME, LBL_NOME, true, $obj->getNome());
            //data collaudo
            parent::printDatePickerFormField($tipo.'-'.FRM_COLLAUDO_DATA, LBL_COLLAUDO_DATA, false, $obj->getDataCollaudo());
            //stato
            parent::printSelectFormField($tipo.'-'.FRM_COLLAUDO_STATO, LBL_STATO, $this->statoCollaudo, true, $obj->getStato());            
            //note
            parent::printTextAreaFormField($tipo.'-'.FRM_COLLAUDO_NOTE, LBL_NOTE, false, $obj->getNote());
            //responsabili
            parent::printMultiSelectAdvancedFormField($tipo.'-'.FRM_COLLAUDO_RESPONSABILI, LBL_COLLAUDO_RESPONSABILI, $arrayCantieristi, false, $obj->getResponsabili());
            
            //ruoli
            $this->printDettaglioRuoli($obj->getRuoli(), $obj->getID(), $arrayForm, $tipo);
            
            //importa voci gruppo da precollaudo            
            if($tipo == 'collaudo' && ($obj->getGruppoVoci() == null || count($obj->getGruppoVoci()) == 0) && $idPreCollaudo != null){
                echo '<a id="'.$obj->getID().'" data-precollaudo="'.$idPreCollaudo.'" class="carica-precollaudo btn">CARICA PRECOLLAUDO</a>';
            }
            
            //modifica Gruppi Voce
            echo '<a href="'. home_url().'/'.FRM_COLLAUDO.'/dettaglio-'.FRM_COLLAUDO.'?ID='.$obj->getID().'" class="visualizza-gv btn">Modifica Gruppi Voce</a>';
            
            //bottone-bacheca
            parent::printButtonUrl('Vedi Bacheca', 'bacheca-'.FRM_COLLAUDO.'/?ID='.$obj->getID() );
            
            //Bottone PDF
            if($obj->getStato() == COLLAUDO_STATO_COMPLETATO){
                //visualizzo solo in caso di collaudo completato
                if($obj->getPdf() == null){
                    echo '<a class="genera-pdf btn" data-id="'.$obj->getID().'">GENERA PDF</a>';
                }
                else{
                    echo '<a class="genera-pdf btn" data-id="'.$obj->getID().'">GENERA PDF</a>';
                    echo '<a class="btn" target="_blank" href="'.$obj->getPdf().'">VISUALIZZA PDF</a>';
                }
            }
            
        parent::printEndDetailsForm($tipo);
        
    }
    
    protected function printDettaglioRuoli($array, $idCollaudo, $arrayForm, $tipo){
        echo '<h5>Ruoli</h5>';        
        if(checkResult($array)){
            foreach($array as $item){
                $ruoloc = updateToRuoloC($item);
                $idUtente = getIdUtente($ruoloc->getID(), $idCollaudo);
                parent::printChosenSelectFormField($tipo.'-'.FRM_RUOLO.'-'.$ruoloc->getID(), $ruoloc->getNome(), $arrayForm, false, $idUtente);               
            }
        }
        else{
            echo '<p>Non sono stati assegnati ruoli</p>';
        }
                
    }
    
    protected function checkCollaudoFields($tipo){
        $errors = 0;
        $c = new Collaudo();
        
        //ID
        if(isset($_POST[$tipo.'-'.DBT_IDCOLLAUDO])){
            $c->setID($_POST[$tipo.'-'.DBT_IDCOLLAUDO]);
        }
        //id cantiere
        if(isset($_POST[$tipo.'-'.DBT_IDCANTIERE])){
            $c->setIdCantiere($_POST[$tipo.'-'.DBT_IDCANTIERE]);
        }
        //nome - obbligatorio
        if(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_NOME, LBL_NOME) !== false){
            $c->setNome(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_NOME, LBL_NOME));
        }
        else{
            $errors++;
        }
        //data collaudo
        if(parent::checkSingleField($tipo.'-'.FRM_COLLAUDO_DATA) !== false){
            $c->setDataCollaudo(parent::checkSingleField($tipo.'-'.FRM_COLLAUDO_DATA));
        }
        //note
        if(parent::checkSingleField($tipo.'-'.FRM_COLLAUDO_NOTE) !== false){
            $c->setNote(parent::checkSingleField($tipo.'-'.FRM_COLLAUDO_NOTE));
        }
        //tipo - obbligatorio
        if(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_TIPO, LBL_TIPO) !== false){
            $c->setTipo(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_TIPO, LBL_TIPO));
        }
        else{
            $errors++;
        }
        //stato - obbligatorio
        if(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_STATO, LBL_STATO) !== false){
            $c->setStato(parent::checkRequiredSingleField($tipo.'-'.FRM_COLLAUDO_STATO, LBL_STATO));
        }
        else{
            $errors++;
        }
        
        //responsabili 
        if(parent::checkMultipleSelectField($tipo.'-'.FRM_COLLAUDO_RESPONSABILI, LBL_COLLAUDO_RESPONSABILI) !== false){
            $c->setResponsabili(parent::checkMultipleSelectField($tipo.'-'.FRM_COLLAUDO_RESPONSABILI, LBL_COLLAUDO_RESPONSABILI));
        }
        
        
        //ruoli
        $c->setRuoli($this->checkRuoliFields($tipo, $c->getID()));
        
        if($errors > 0){
            return null;
        }
        return $c;        
        
    }
    
    
    protected function checkRuoliFields($tipo, $idCollaudo){
        $result = array();
        foreach($_POST as $key => $value){
            if(strpos($key, $tipo.'-'.FRM_RUOLO.'-') !== false ){                
                //costruisco la struttura dei ruoli                
                //ottengo l'id del ruolo
                $temp1 = explode($tipo.'-'.FRM_RUOLO.'-', $key);
                $temp2 = explode('utentec-', $value);
                $temp3 = explode('azienda-', $value);
                if($temp1 != null && count($temp1) > 0 && $temp2 != null && count($temp2) > 1){
                    $ur = new UtentecRuoloc();
                    $ur->setIdCollaudo(intval($idCollaudo));
                    $ur->setIdRuoloC(intval($temp1[1]));
                    $ur->setIdUtenteC(intval($temp2[1]));
                    $ur->setIdAzienda(null);
                    array_push($result, $ur);
                    unset($ur);
                } 
                else if($temp1 != null && count($temp1) > 0 && $temp3 != null && count($temp3) > 1){
                    $ur = new UtentecRuoloc();
                    $ur->setIdCollaudo(intval($idCollaudo));
                    $ur->setIdRuoloC(intval($temp1[1]));
                    $ur->setIdUtenteC(null);
                    $ur->setIdAzienda(intval($temp3[1]));
                    array_push($result, $ur);
                    unset($ur);
                }
            }
        }
        return $result;
        
    }      
    
    /************************ LOG ************************/
    
    public function printCollaudoLog($idCollaudo){
                
        //ottengo i log
        $where = array(
            array(
                'campo'     => DBT_IDCOLLAUDO,
                'valore'    => $idCollaudo,
                'formato'   => 'INT'
            )
        );
        
        $total = 0;
        $limit = ELEMENTI_PER_PAGINA;
        $page_num = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
        $offset = ( $page_num - 1 ) * $limit;
        
        //trovo i totali
        $logsTotal = $this->collaudo->getLogs($where);
        $total = count($logsTotal);
                
        //trovo quelli da far visualizzare per pagina
        $logs = $this->collaudo->getLogs($where, $offset);
        
        $num_of_pages = ceil( $total / $limit ); 
        
        if(checkResult($logs)){
        
            $page_links = paginate_links( array(
                'base' => add_query_arg( 'pagenum', '%#%' ),
                'format' => '',
                'prev_text' => __( '«', 'text-domain' ),
                'next_text' => __( '»', 'text-domain' ),
                'total' => $num_of_pages,
                'current' => $page_num
                ) );
            
            $this->printLogTableResult($logs);
            
            if ( $page_links ) {
                echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0;">' . $page_links . '</div></div>';
            }
            
        }
        
    }
    
    
    protected function printLogTableResult($array){
        $stato = array(
            VOCE_STATO_ROSSO    => 'Da iniziare',
            VOCE_STATO_GIALLO   => 'Da verificare',
            VOCE_STATO_VERDE    => 'Verificato'
        );
        
        if(checkResult($array)){
           $header = array('GRUPPO VOCE', 'VOCE', 'UTENTE', 'STATO', 'DATA MODIFICA');
           $rows = array();
           foreach($array as $item){
                $log = updateToLog($item);
                $colonne = array(
                    getTitoloGruppoVoce($log->getIdGV()),
                    getTitoloVoce($log->getIdVoce()),
                    getNiceName($log->getUtenteWP()),
                    $stato[$log->getStato()],
                    translateToDate($log->getDataOperazione(), true)
                );
                array_push($rows, $colonne);
           }
           parent::printTableHover($header, $rows);
        }
        else{
            parent::printNoResults('LOG');
        }
    }
}
