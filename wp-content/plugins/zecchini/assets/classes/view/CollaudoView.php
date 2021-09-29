<?php

namespace zecchini;

class CollaudoView  extends PrinterView implements InterfaceView{
    private $collaudo;
    private $utente;
    private $tipo;
    private $tipoVoce;    
    function __construct() {
        parent::__construct();
        $this->collaudo = new CollaudoController();
        $this->utente = new UtenteController();
        $this->tipo = array(
            TIPO_PRECOLLAUDO    => 'Precollaudo',
            TIPO_COLLAUDO       => 'Collaudo'
        );
        
        $this->tipoVoce = array(
            VOCE_TIPO_NESSUNA   => 'Nessuno',
            VOCE_TIPO_SINO      => 'Si/No',
            VOCE_TIPO_TESTO     => 'Campo testuale'
        );
        
    }
    

    public function listenerSaveForm() {
        if(isset($_POST[FRM_SAVE.FRM_COLLAUDO])){
            //il salvataggio avviene in tre momenti
            //1. Salvo il collaudo e ne ottengo l'ID
            //2. Salvo i ruoli e ne ottengo l'ID
            //3. Salvo i Collaudi/Ruolo con gli ID ottenuti
            
            //ottengo il collaudo
            $collaudo = $this->checkFields();
            //salvo il collaudo
            if($collaudo == null){
                return null;
            }
            $idCollaudo = $this->collaudo->saveCollaudo($collaudo);
            
            if($idCollaudo > 0){
                $ruoli = $this->checkRuoli();
                //salvo i ruoli nel DB
                foreach($ruoli as $item){
                    
                    $ruolo = updateToRuoloC($item);
                    $idRuolo = $this->utente->saveRuolo($ruolo);                    
                    if($idRuolo > 0){
                        $colRuo = new CollaudoRuoloc();
                        $colRuo->setIdCollaudo($idCollaudo);
                        $colRuo->setIdRuoloC($idRuolo);

                        if(!$this->collaudo->saveCollaudoRuoloC($colRuo)){
                            return false;
                        }
                    }
                }    
                $this->printMessaggeAfterSave(LBL_COLLAUDO, $idCollaudo);
            }
            
        }
    }
    
    public function printSaveForm() {
        parent::printStartAddForm(FRM_COLLAUDO);
            //tipo collaudo
            parent::printSelectFormField(FRM_COLLAUDO_TIPO, LBL_TIPO, $this->tipo, true);
            //nome
            parent::printTextFormField(FRM_COLLAUDO_NOME, LBL_NOME, true);
            //data collaudo
            parent::printDatePickerFormField(FRM_COLLAUDO_DATA, LBL_COLLAUDO_DATA);
            //note
            parent::printTextAreaFormField(FRM_COLLAUDO_NOTE, LBL_NOTE);
            
            //ruoli
            $this->printRuoli();
            
        parent::printEndAddForm(FRM_COLLAUDO);
    }
    
    public function listenerDetailsForm() {
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_COLLAUDO])){           
            //l'aggiornamento avviene in tre fasi
            //1. aggiorno il collaudo
            //2. elimino le associazioni collaudo/ruolo
            //3. salvo i nuovi ruoli (se presenti)
            //4. ri-associo i ruoli con il collaudo
            $collaudo = $this->checkFields();            
            if($collaudo == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $update = $this->collaudo->updateCollaudo($collaudo);
            //trovo i ruoli assegnati
            $ruoli = $this->checkRuoli();            
            //elimino le associazioni collaudo/ruoli
            if($this->collaudo->deleteCollaudoRuoloC(array(DBT_IDCOLLAUDO => $collaudo->getID()))){   
                
                foreach($ruoli as $item){                    
                    $ruolo = updateToRuoloC($item);
                    $idRuolo = $this->utente->saveRuolo($ruolo);   
                    
                    if($idRuolo > 0){
                        $colRuo = new CollaudoRuoloc();
                        $colRuo->setIdCollaudo($collaudo->getID());
                        $colRuo->setIdRuoloC($idRuolo);                                              
                        if(!$this->collaudo->saveCollaudoRuoloC($colRuo)){
                            return false;
                        }
                    }
                } 
                
                parent::printMessageAfterUpdate($update);
            }
            return null;
            
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_COLLAUDO])){
            //elimino il collaudo e le associazioni collaudo/ruolo
            //I ruoli non vanno eliminati
            if($this->collaudo->deleteCollaudoRuoloC(array(DBT_IDCOLLAUDO => $_GET['ID']))){  
                //elimino il collaudo
                $delete = $this->collaudo->deleteCollaudo($_GET['ID']);
                parent::printMessageAfterDelete($delete);
            }
        }
    }

    public function printDetailsForm($ID) {
    ?>
        <div role="tabpanel" class="tab-pane active" id="collaudo">
    <?php
        $temp = $this->collaudo->getCollaudoByID($ID);
        if($temp != null){
            $collaudo = updateToCollaudo($temp);
            //var_dump($collaudo);
            parent::printStartDetailsForm(FRM_COLLAUDO);
                parent::printHiddenFormField(FRM_ID, $collaudo->getID());
                parent::printHiddenFormField(FRM_COLLAUDO_IDCANTIERE, $collaudo->getIdCantiere());
                //tipo collaudo
                parent::printSelectFormField(FRM_COLLAUDO_TIPO, LBL_TIPO, $this->tipo, true, $collaudo->getTipo());
                //nome
                parent::printTextFormField(FRM_COLLAUDO_NOME, LBL_NOME, true, $collaudo->getNome());
                //data collaudo
                parent::printDatePickerFormField(FRM_COLLAUDO_DATA, LBL_COLLAUDO_DATA, false, $collaudo->getDataCollaudo());
                //note
                parent::printTextAreaFormField(FRM_COLLAUDO_NOTE, LBL_NOTE, false, $collaudo->getNote());
                
                //ruoli
                $this->printRuoli($collaudo->getRuoli());                
            parent::printEndDetailsForm(FRM_COLLAUDO);
        }
        else{
            echo '<p>Collaudo non trovato</p>';
        }
        ?>
        </div>    
        <?php    
    }
    
    protected function checkFields(){
        $errors = 0;
        $collaudo = new Collaudo();
        
        //ID
        if(isset($_POST[FRM_ID])){
            $collaudo->setID($_POST[FRM_ID]);            
        }
        //ID COLLAUDO -> se c'è è un update, se non c'è è un salvataggio di collaudo
        if(parent::checkSingleField(FRM_COLLAUDO_IDCANTIERE) !== false){
            $collaudo->setIdCantiere(parent::checkSingleField(FRM_COLLAUDO_IDCANTIERE));
        }
        else{
            $collaudo->setIdCantiere(0);
        }
        
        //tipo - obbligatorio
        if(parent::checkRequiredSingleField(FRM_COLLAUDO_TIPO, LBL_TIPO) !== false){
            $collaudo->setTipo(parent::checkRequiredSingleField(FRM_COLLAUDO_TIPO, LBL_TIPO));
        }
        else{
            $errors++;
        }
        //nome - obbligatorio
        if(parent::checkRequiredSingleField(FRM_COLLAUDO_NOME, LBL_NOME) !== false){
            $collaudo->setNome(parent::checkRequiredSingleField(FRM_COLLAUDO_NOME, LBL_NOME));
        }
        else{
            $errors++;
        }
        //data collaudo
        if(parent::checkSingleField(FRM_COLLAUDO_DATA) !== false){
            $collaudo->setDataCollaudo(parent::checkSingleField(FRM_COLLAUDO_DATA));
        }
        //note
        if(parent::checkSingleField(FRM_COLLAUDO_NOTE) !== false){
            $collaudo->setNote(parent::checkSingleField(FRM_COLLAUDO_NOTE));
        }        
        if($errors > 0){
            return null;
        }
        return $collaudo;
        
    }
    
    public function printListCollaudi(){
        $collaudi = $this->collaudo->getModelliCollaudo();        
        $this->printCollaudiTableResult($collaudi);
        
    }
    
    private function printCollaudiTableResult($array){
        if(checkResult($array)){
            $tipo = $this->tipo;
            echo '<h4>Collaudi trovati: '.count($array).'</h4>';
            $header = array(LBL_NOME, LBL_TIPO, 'AZIONI');
            $rows = array();
            foreach($array as $item){
                $collaudo = updateToCollaudo($item);
                $colonne = array(
                    $collaudo->getNome(),
                    $tipo[$collaudo->getTipo()],
                    parent::getDetailsButton('dettaglio-'.FRM_COLLAUDO, $collaudo->getID())
                );
                array_push($rows, $colonne);
            }
            parent::printTableHover($header, $rows);
        }
        else{
            parent::printNoResults(LBL_COLLAUDO);
        }
    }
    
    
    /************ RUOLO *******************/
    private function printRuoli($arrayRuoli = null){
    ?>
        <script>
            jQuery( function($) {
                var ruoli = [<?php echo parent::printArraySuggestion($this->utente->getRuoliPerSuggerimenti()) ?>];
                $(document.body).on('focus', '.nome-ruolo input', function(){
                    $(this).autocomplete({
                        source: ruoli                                            
                    });
                });
            } );
            
        </script>
        <h4>Ruoli</h4>
        <div class="container-ruoli">
            <?php 
                if($arrayRuoli == null ){            
                    $this->printRuolo(1)  ;
                }
                else{
                    $counter = 1;
                    foreach($arrayRuoli as $item){
                        $ruolo = updateToRuoloC($item);
                        $this->printDettaglioRuolo($ruolo, $counter);
                        $counter++;                        
                    }
                }
                    
            ?>
        </div>
        <div class="aggiungi-ruolo">
            <a class="btn">Aggiungi Ruolo</a>
        </div>
    <?php        
    }
    
    
    private function printRuolo($counter){
    ?>
        <div class="ruolo" data-num="<?php echo $counter ?>">
            <div class="countruolo"><?php parent::printHiddenFormField('count-ruolo-'.$counter, $counter ) ?></div>
            <div class="nome-ruolo"><?php parent::printTextFormField(FRM_RUOLO_NOME.'-'.$counter, LBL_NOME, true) ?></div>
            <div class="rimuovi-ruolo">
                <a class="btn">Rimuovi Ruolo</a>
            </div>
            <hr>
        </div>
    <?php
    }
    
    private function printDettaglioRuolo(RuoloC $obj, $counter){
    ?>
        <div class="ruolo" data-num="<?php echo $counter ?>">
            <div class="countruolo"><?php parent::printHiddenFormField('count-ruolo-'.$counter, $counter ) ?></div>
            <div class="id-ruolo"><?php parent::printHiddenFormField('id-ruolo-'.$counter, $obj->getID()) ?></div>
            <div class="nome-ruolo"><?php parent::printTextFormField(FRM_RUOLO_NOME.'-'.$counter, LBL_NOME, true, $obj->getNome()) ?></div>
            <div class="rimuovi-ruolo">
                <a class="btn">Rimuovi Ruolo</a>
            </div>
            <hr>
        </div>
    <?php    
    }
    
    protected function checkRuoli(){
        $result = array();
        $errors = 0;
        foreach($_POST as $key => $value){
            if(strpos($key, 'count-ruolo') !== false){
                $ruolo = new RuoloC();
                if(parent::checkRequiredSingleField(FRM_RUOLO_NOME.'-'.$value, LBL_NOME) !== false){
                    $ruolo->setNome(parent::checkRequiredSingleField(FRM_RUOLO_NOME.'-'.$value, LBL_NOME));
                }
                else{
                    $errors++;
                }
                if($errors > 0){
                    return null;
                }
                array_push($result, $ruolo);
                unset($ruolo);
            }
        }
        return $result;
    }

    
    /************************ GRUPPO VOCI ************************/
    
    public function listenerGVSaveForm(){
        
        if(isset($_POST[FRM_SAVE.FRM_GV])){            
            $temp = $this->checkGVFields();
            if($temp == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return ;
            }
            $gv = updateToGV($temp);
            $save = $this->collaudo->saveGruppoVoce($gv);
            $this->printMessaggeAfterSave(LBL_GV, $save);
        }
    }
    
    
    public function printGVSaveForm($idCollaudo){
    ?>
        <div role="tabpanel" class="tab-pane" id="salvagruppovoce">
            <h4>Inserisci Gruppo Voce</h4>
    <?php    
        
        parent::printStartAddForm(FRM_GV);
            parent::printHiddenFormField(DBT_IDCOLLAUDO, $idCollaudo);
            parent::printTextFormField(FRM_GV_TITOLO, LBL_TITOLO, true);
            parent::printHiddenFormField('count-gv-0', 0);
            //devo indicare chi può vedere questo ruolo
            $this->printVisibilitaForm($idCollaudo);            
            
            //Voci            
            $this->printVociForm();
            
        parent::printEndAddForm(FRM_GV);
    ?>
        </div>  
    <?php
    }
    
    public function listenerGVDetailForm(){
        //1. AGGIORNAMENTO
        if(isset($_POST[FRM_UPDATE.FRM_GV])){
            $temp = $this->checkGVFields();
            if($temp == null){
                parent::printErrorBoxMessage('I dati inseriti non sono corretti');
                return;
            }
            $gv = updateToGV($temp);     
                        
            $update = $this->collaudo->updateGruppoVoce($gv);
            parent::printMessageAfterUpdate($update);            
        }
        //2. CANCELLAZIONE
        if(isset($_POST[FRM_DELETE.FRM_GV])){
            $idGV = $_POST[DBT_ID];
            $delete = $this->collaudo->deleteGruppoVoce($idGV);
            parent::printMessageAfterDelete($delete);
        }
    }
    
    
    public function printGVDetailForm($idCollaudo){
    ?>
        <div role="tabpanel" class="tab-pane" id="dettagligruppovoce">
            <h4>Gruppi Voce</h4>
    <?php 
            //ottengo i gruppi voce
            $temp = $this->collaudo->getGruppoVociByIdCollaudo($idCollaudo);
            $ruoli = $this->collaudo->getArrayRuoliInCollaudo($idCollaudo); 
            if(checkResult($temp)){
                $counter = 1;
                foreach($temp as $item){                    
                    $gv = updateToGV($item);
                    echo '<div class="container-gruppo-voce">';
                    parent::printStartDetailsForm(FRM_GV);
                        parent::printHiddenFormField('count-gv-'.$counter, $counter);
                        parent::printHiddenFormField(DBT_ID, $gv->getID());
                        parent::printHiddenFormField(DBT_IDCOLLAUDO, $gv->getIdCollaudo());
                        parent::printTextFormField(FRM_GV_TITOLO, LBL_TITOLO, true, $gv->getTitolo());
                        //visibilità
                        echo '<div class="container-visibilita">';
                        parent::printMultiSelectAdvancedFormField(FRM_GV_VISIBILITA.'-'.$counter, LBL_VISIBILITA, $ruoli, true, $gv->getVisibilita());
                        echo '</div>';
                        echo '<div class="container-aggiorna-visiblita" style="float:right"><a data-idGV="'.$gv->getID().'" class="aggiorna-visibilita btn">AGGIORNA VISIBILIT&Agrave;</a></div>';                        
                        echo '<div class="clear"></div>';
                        
                        //Voci
                        $this->printVociForm($gv->getVoci());
                        
                    parent::printEndDetailsForm(FRM_GV);
                    echo '</div>';                    
                    $counter++;
                }
            }
            else{
                echo '<p>Gruppi Voce non presenti</p>';
            }
    
            
    ?>
        </div>
    <?php        
    }
    
    
    private function printVisibilitaForm($idCollaudo){        
        //devo ottenere i ruoli passato un determinato ID Collaudo
        $ruoli = $this->collaudo->getArrayRuoliInCollaudo($idCollaudo);            
        parent::printMultiSelectAdvancedFormField(FRM_GV_VISIBILITA.'-0', LBL_VISIBILITA, $ruoli, true);
        
    }
    
    private function printVociForm($arrayVoci = null, $idGV = null){
    ?>    
        <!-- Riporto le variabili php da passare poi al file js -->
        <script type="text/javascript">
            var descrizioneVoce = '<?php echo FRM_VOCE_DESCRIZIONE ?>';
            var pesoVoce = '<?php echo FRM_VOCE_PESO ?>';
            var dlrVoce = '<?php echo FRM_VOCE_DLRISOLUZIONE ?>';
            var dvuVoce = '<?php echo FRM_VOCE_DVULTIMAZIONE ?>';
            var noteVoce = '<?php echo FRM_VOCE_NOTE ?>';
            var tipoVoce = '<?php echo FRM_VOCE_TIPO ?>';
        </script>   
        <h5 style="padding-left:15px;">Voci</h5>
        <div class="mostranascondi-container-voci"><span class="testo">Mostra</span><span class="icona"></span></div>
        <div class="clear"></div>
        <div class="container-voci" style="padding-left:15px;">
             
            <?php 
                if($arrayVoci == null || count($arrayVoci) == 0){
                    $this->printVoceForm(1);
                }
                else{
                    $counter = 1;
                    foreach($arrayVoci as $item){
                        $voce = updateToVoce($item);
                        $this->printDetailVoceForm($voce, $counter);
                        $counter++;
                    }
                }
            ?>
        </div>
        <div class="aggiungi-voce">
            <a class="btn">Aggiungi voce</a>
        </div>
        
    <?php    
    }
    
    private function printVoceForm($counter){
    ?>        
        <div class="voce" data-num="<?php echo $counter ?>">
            <div class="countvoce"><?php parent::printHiddenFormField('count-voce-'.$counter, $counter ) ?></div>
            <div class="descrizione"><?php parent::printTextAreaFormField(FRM_VOCE_DESCRIZIONE.'-'.$counter, LBL_VOCE_DESCRIZIONE, true) ?></div>
            <div class="peso"><?php parent::printNumberFormField(FRM_VOCE_PESO.'-'.$counter, LBL_VOCE_PESO, true, 1) ?></div>
            <div class="tipo"><?php parent::printSelectFormField(FRM_VOCE_TIPO.'-'.$counter, LBL_TIPO, $this->tipoVoce, true) ?></div>
            <div class="btn rimuovi-voce">
                <a>Rimuovi voce</a>
            </div>   
            <hr>
        </div>
        
    <?php    
    }
    
    private function printDetailVoceForm(Voce $obj, $counter){
    ?>
        <div class="voce" data-num="<?php echo $counter ?>">
            <div class="countvoce"><?php parent::printHiddenFormField('count-voce-'.$counter, $counter ) ?></div>
            <div class="id-voce"><?php parent::printHiddenFormField('id-voce-'.$counter, $obj->getID()) ?></div>
            <div class="id-gv"><?php parent::printHiddenFormField('id-gv-'.$counter, $obj->getIdGruppoVoce()) ?></div>
            <div class="descrizione"><?php parent::printTextAreaFormField(FRM_VOCE_DESCRIZIONE.'-'.$counter, LBL_VOCE_DESCRIZIONE, true, $obj->getDescrizione()) ?></div>
            <div class="peso"><?php parent::printNumberFormField(FRM_VOCE_PESO.'-'.$counter, LBL_VOCE_PESO, true, $obj->getPeso()) ?></div>
            <div class="tipo"><?php parent::printSelectFormField(FRM_VOCE_TIPO.'-'.$counter, LBL_TIPO, $this->tipoVoce, true, $obj->getTipo()) ?></div>
            
            <div class="btn aggiorna-voce">
                <a>Aggiorna voce</a>
            </div>
            
            <div class="btn rimuovi-voce">
                <a>Rimuovi voce</a>
            </div>  
        </div>
            
    <?php    
    }
    

    protected function checkGVFields(){
        $errors = 0;
        $gv = new GruppoVoce();
        
        //ID
        if(isset($_POST[DBT_ID])){
            $gv->setID($_POST[DBT_ID]);
            //effettuo altri controlli per l'update
            $temp = updateToGV($this->collaudo->getGruppoVoceByID($gv->getID()));
            //stato
            $gv->setStato($temp->getStato());  
        }
        else{
            //salvataggio e non update
            $gv->setStato(VOCE_STATO_ROSSO);
        }
        //id collaudo
        if(isset($_POST[DBT_IDCOLLAUDO])){
            $gv->setIdCollaudo($_POST[DBT_IDCOLLAUDO]);
        }
        //titolo - obbligatorio
        if(parent::checkRequiredSingleField(FRM_GV_TITOLO, LBL_TITOLO) !== false){
            $gv->setTitolo(parent::checkRequiredSingleField(FRM_GV_TITOLO, LBL_TITOLO));
        }
        else{
            $errors++;
        }
        //visibilita
        foreach($_POST as $key => $value){            
            $valore = 0;
            if(strpos($key, 'count-gv-') !== false){
                $valore = $_POST['count-gv-'.$value];                
                if(parent::checkMultipleSelectField(FRM_GV_VISIBILITA.'-'.$valore) !== false){
                    $gv->setVisibilita(parent::checkMultipleSelectField(FRM_GV_VISIBILITA.'-'.$valore));
                }
            }
        }                
        $gv->setVoci($this->checkVociFields());        
        if($errors > 0){
            return null;
        }        
        //var_dump($_POST);        
        //var_dump($gv);
        //die();        
        return $gv;        
    }
    
    protected function checkVociFields(){
        $result = array();
        $errors = 0;
        foreach($_POST as $key => $value){
            if(strpos($key, 'count-voce') !== false){
                $voce = new Voce();
                if(isset($_POST['id-voce-'.$value])){
                    $voce->setID($_POST['id-voce-'.$value]);
                    //ottengo altri campi
                    $t = $this->collaudo->getVoceByID($voce->getID());
                    if($t != null){
                        $temp = updateToVoce($t);
                        //stato
                        $voce->setStato($temp->getStato());
                        //idgruppovoce
                        $voce->setIdGruppoVoce($temp->getIdGruppoVoce());
                    }
                }
                else{
                    //vuol dire che sto salvando e non aggiornado quindi alcuni valori bisogna impostarli a livello di salvataggio
                    $voce->setStato(VOCE_STATO_ROSSO);                    
                }
                //descrizione - obbligatorio
                if(parent::checkRequiredSingleField(FRM_VOCE_DESCRIZIONE.'-'.$value, LBL_VOCE_DESCRIZIONE) !== false){
                    $voce->setDescrizione(parent::checkRequiredSingleField(FRM_VOCE_DESCRIZIONE.'-'.$value, LBL_VOCE_DESCRIZIONE));                    
                }
                else{
                    $errors++;
                }
                //peso - obbligatorio
                if(parent::checkRequiredSingleField(FRM_VOCE_PESO.'-'.$value, LBL_VOCE_PESO) !== false){
                    $voce->setPeso(parent::checkRequiredSingleField(FRM_VOCE_PESO.'-'.$value, LBL_VOCE_PESO));
                }
                else{
                    $errors++;
                }
                //tipo - obbligatorio
                if(parent::checkRequiredSingleField(FRM_VOCE_TIPO.'-'.$value, LBL_TIPO) !== false){
                    $voce->setTipo(parent::checkRequiredSingleField(FRM_VOCE_TIPO.'-'.$value, LBL_TIPO));
                }
                else{
                    $errors++;
                }
                
                if($errors > 0){
                    return null;
                }
                //carico la voce nell'array
                array_push($result, $voce);
                //spacco l'oggetto
                unset($ec);
            }
        }
        return $result;
    }
    
    
    /*********************************** BACHECA **************************************/
    
    public function listenerBachecaCollaudo(){
        if(isset($_POST[FRM_UPDATE.FRM_GV])){
            //ottengo un array di voci
            //var_dump($_POST);
            $temp = $this->checkBachecaFields();
            if(checkResult($temp)){
                foreach($temp as $item){
                    $voce = updateToVoce($item);
                    $update = $this->collaudo->updateVoce($voce);    
                    
                    //creo anche il log da poter salvare
                    $log = new Log();
                    $log->setIdCollaudo($_GET['idc']); //lo trovo nell'url della pagina
                    $log->setIdGV($voce->getIdGruppoVoce());
                    $log->setIdVoce($voce->getID());
                    $log->setUtenteWP(get_current_user_id());
                    $log->setStato($voce->getStato());
                    $this->collaudo->saveLog($log);
                }
                //controllo sulle voci per l'eventuale invio di email
                $this->collaudo->controlloIvioMail($temp, get_current_user_id());
                
                parent::printMessageAfterUpdate($update);
            }
        }
        
        if(isset($_POST[FRM_SAVE.FRM_COMMENTO])){
            //var_dump($_POST);
            $temp = $this->checkCommentoFields();
            if($temp !== null){
                $commento = updateToCommento($temp);
                $save = $this->collaudo->saveCommento($commento);
                parent::printMessaggeAfterSave('Commento', $save);
            }
        }
    }
    
    /*    
    public function printBachecaCollaudo(Collaudo $obj){
        
        $arrayStatoVoce = array();
        $isResponsabile = isResponsabile($obj->getResponsabili(), get_current_user_id());
        //MODIFICA --> possono mettere il verde anche i collaudatori nel collaudo (nel precollaudo i collaudatori teoricamente non ci sono)
        $isCollaudatore = isCollaudatore(get_current_user_id(), $obj->getID());
             
        if($isResponsabile || $isCollaudatore){
            $arrayStatoVoce = array(
                1 => 'ROSSO',
                2 => 'GIALLO',
                3 => 'VERDE'
            );
        }
        else{
            $arrayStatoVoce = array(
                1 => 'ROSSO',
                2 => 'GIALLO',                
            );
        }
        
        
        if(isAdmin()){
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'/cantiere/dettaglio-cantiere/?ID='.$obj->getIdCantiere().'">Ritorna a CANTIERE</a></div>';
        }
        else{
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'">Ritorna a HOME PAGE</a></div>';
        }
        
        //devo ottenere i gruppi voce
        $gvs = $obj->getGruppoVoci();    
        
        $ruoli = $this->utente->getNomeRuoliUtente($obj->getID());
        if(checkResult($ruoli)){
            echo '<p class="ruoli-assegnati" style="margin-bottom:50px;">';
            echo '<strong>Ruoli assegnati: </strong>';
            $count = 1;
            foreach($ruoli as $item){
                echo $item;
                if($count < count($ruoli)){
                    echo ', ';
                }

                $count++;
            }        
            echo '</p>';
        }
        
        if(checkResult($gvs)){            
            echo '<div class="container-gruppi-voce">';
            $counterGV = 1;
            foreach($gvs as $item){
                $gv = updateToGV($item);
                //funzione che mi indica se posso leggere il gruppo voce 
                //MODIFICA: per semplicità anche il collaudatore può vedere tutte le voci. Questo perchè i collaudatori non esistono nel precollaudo ed il collaudo è una copia dei dati del precollaudo e di conseguenza in automatico non vengono inseriti nelle visibilità.
                if(canReadGV($gv, get_current_user_id(), $isResponsabile) === true || $isCollaudatore){                    
                    echo '<div class="container-bacheca-gruppo-voce">';
                        parent::printStartDetailsForm(FRM_GV);
                        parent::printHiddenFormField(DBT_IDGV, $gv->getID());
                        echo '<div class="header-gruppo-voce">';
                            echo '<div class="num-progressivo"><strong>'.$counterGV.'</strong></div>';
                            echo '<div class="titolo-gruppo-voce"><strong>'.$gv->getTitolo().'</strong></div>';
                            echo '<div class="completamento">'.$this->calcoloCompletamentoGV($gv).'<div class="mostranascondi-voci"><span class="icona"></span></div></div>';                            
                            echo '<div class="clear"></div>';
                        echo '</div>';
                        //entro nel dettaglio delle voci
                        echo '<div class="container-voci" style="display:none">';
                        if(checkResult($gv->getVoci())){
                            $counterV = 1;
                            foreach($gv->getVoci() as $item){
                                $voce = updateToVoce($item);
                                echo '<div class="container-voce">';
                                    //echo '<div class="num-progressivo sinistra"><strong>'.$counterGV.'.'.$counterV.'</strong></div>';
                                    
                                    
                                    echo '<div class="destra">';                                        
                                        echo '<div class="date">';
                                            parent::printDatePickerFormField(FRM_VOCE_DLRISOLUZIONE.'-'.$voce->getID(), LBL_VOCE_DLRISOLUZIONE, false, $voce->getDataLimiteRisoluzione());
                                            parent::printDatePickerFormField(FRM_VOCE_DVULTIMAZIONE.'-'.$voce->getID(), LBL_VOCE_DVULTIMAZIONE, false, $voce->getDataVerificaUltimazione());
                                        echo '</div>';
                                        echo '<div class="stato-voce">';
                                        $this->printSemafori($voce, $isResponsabile, $obj);                                            
                                        echo '</div>';
                                    echo '</div>';
                                    
                                    echo '<div class="dati-voce centro">';                                        
                                        parent::printHiddenFormField(DBT_IDVOCE.'-'.$voce->getID(), $voce->getID());
                                                                                
                                        echo '<div class="descrizione"><strong>'.$counterGV.'.'.$counterV.'</strong> '.$voce->getDescrizione().'</div>'; 
                                        echo '<div class="esito">';
                                        //esito
                                        $this->printEsito($voce->getTipo(), $voce->getID(), $voce->getEsito());
                                        echo '</div>';
                                        
                                        //note
                                        $this->printNote($voce->getID(), $voce->getNote());


                                        echo '<div class="container-commenti">';
                                        echo '<h5>Commenti salvati</h5>';
                                            $this->printCommenti($voce->getCommenti());
                                        echo '</div>';

                                        echo '<a class="btn btn-aggiungi-commento">AGGIUNGI COMMENTO</a>';
                                        echo '<div class="clear"></div>';
                                        echo '<div class="aggiungi-commento">';
                                            $this->printAggiungiCommento($voce->getID());
                                        echo '</div>';
                                    echo '</div>';
                                    
                                    echo '<div class="clear"></div>';
                                echo '</div>';
                                $counterV++;
                            }
                        }
                        else{
                            echo '<p>Non sono presenti voci per questo gruppo voce</p>';
                        }                    

                        echo '</div>';
                        parent::printEndDetailsForm(FRM_GV);
                    echo '</div>';
                }
                //var_dump($gv);
                $counterGV++;
            }
            
            echo '</div>';
        }
        else{
            echo '<p>Non è stata trovata alcuna voce</p>';
        }           
        
        //var_dump($obj);
    }
    */
    
    public function printBachecaCollaudoTitoli(Collaudo $obj){         
        $isResponsabile = isResponsabile($obj->getResponsabili(), get_current_user_id());
        //MODIFICA --> possono mettere il verde anche i collaudatori nel collaudo (nel precollaudo i collaudatori teoricamente non ci sono)
        $isCollaudatore = isCollaudatore(get_current_user_id(), $obj->getID());
        
        if(isAdmin()){
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'/cantiere/dettaglio-cantiere/?ID='.$obj->getIdCantiere().'">Ritorna a CANTIERE</a></div>';
        }
        else{
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'">Ritorna a HOME PAGE</a></div>';
        }
        
        //devo ottenere i gruppi voce
        $gvs = $obj->getGruppoVoci();    
        
        $ruoli = $this->utente->getNomeRuoliUtente($obj->getID());
        if(checkResult($ruoli)){
            echo '<p class="ruoli-assegnati" style="margin-bottom:50px;">';
            echo '<strong>Ruoli assegnati: </strong>';
            $count = 1;
            foreach($ruoli as $item){
                echo $item;
                if($count < count($ruoli)){
                    echo ', ';
                }

                $count++;
            }        
            echo '</p>';
        }
        
        if(checkResult($gvs)){            
            echo '<div class="container-gruppi-voce">';
            $counterGV = 1;
            foreach($gvs as $item){
                $gv = updateToGV($item);
                //funzione che mi indica se posso leggere il gruppo voce                 
                if(canReadGV($gv, get_current_user_id(), $isResponsabile) === true){                    
                    echo '<div class="container-bacheca-gruppo-voce">';
                        
                        echo '<div class="header-gruppo-voce">';
                            echo '<div class="num-progressivo"><strong>'.$counterGV.'</strong></div>';
                            echo '<div class="titolo-gruppo-voce"><strong>'.$gv->getTitolo().'</strong></div>';
                            echo '<div class="completamento">'.$this->calcoloCompletamentoGV($gv).'</div>';                            
                            echo '<div class="clear"></div>';
                            
                        echo '</div>';
                        echo '<a style="color:#fff; background:rgb(206, 27, 40); display:block; border-radius:none; margin:10px;" class="btn" href="'. home_url().'/bacheca-gv/?idc='.$obj->getID().'&idgv='.$gv->getID().'">APRI GRUPPO VOCE</a>';
                        
                        
                    echo '</div>';
                }
                //var_dump($gv);
                $counterGV++;
            }
            
            echo '</div>';
        }
        else{
            echo '<p>Non è stata trovata alcuna voce</p>';
        }   
        
    }
    
    public function printBachecaGV(Collaudo $obj, $idGV){           
        $arrayStatoVoce = array();
        $isResponsabile = isResponsabile($obj->getResponsabili(), get_current_user_id());
        //MODIFICA --> possono mettere il verde anche i collaudatori nel collaudo (nel precollaudo i collaudatori teoricamente non ci sono)
        $isCollaudatore = isCollaudatore(get_current_user_id(), $obj->getID());
             
        if($isResponsabile || $isCollaudatore){
            $arrayStatoVoce = array(
                1 => 'ROSSO',
                2 => 'GIALLO',
                3 => 'VERDE'
            );
        }
        else{
            $arrayStatoVoce = array(
                1 => 'ROSSO',
                2 => 'GIALLO',                
            );
        }
                
        if(isAdmin()){
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'/cantiere/bacheca-collaudo/?ID='.$obj->getID().'">Ritorna a BACHECA GENERALE</a></div>';
        }
        else{
            echo '<div class="bacheca"><a class="btn" href="'.home_url().'">Ritorna a HOME PAGE</a></div>';
        }
        
        //devo ottenere i gruppi voce
        //$gvs = $obj->getGruppoVoci();    
        
        
        
        $ruoli = $this->utente->getNomeRuoliUtente($obj->getID());
        if(checkResult($ruoli)){
            echo '<p class="ruoli-assegnati" style="margin-bottom:50px;">';
            echo '<strong>Ruoli assegnati: </strong>';
            $count = 1;
            foreach($ruoli as $item){
                echo $item;
                if($count < count($ruoli)){
                    echo ', ';
                }

                $count++;
            }        
            echo '</p>';
        }
        $gv = $this->collaudo->getGruppoVoceByID($idGV);
        
        if($gv != null){            
            echo '<div class="container-gruppi-voce">';
            $counterGV = '';            
                
            //funzione che mi indica se posso leggere il gruppo voce             
            if(canReadGV($gv, get_current_user_id(), $isResponsabile) === true){                    
                echo '<div class="container-bacheca-gruppo-voce">';
                    parent::printStartDetailsForm(FRM_GV);
                    parent::printHiddenFormField(DBT_IDGV, $gv->getID());
                    echo '<div class="header-gruppo-voce">';
                        echo '<div class="num-progressivo"><strong>'.$counterGV.'</strong></div>';
                        echo '<div class="titolo-gruppo-voce"><strong>'.$gv->getTitolo().'</strong></div>';
                        echo '<div class="completamento">'.$this->calcoloCompletamentoGV($gv).'</div>';                            
                        echo '<div class="clear"></div>';
                    echo '</div>';
                    //entro nel dettaglio delle voci
                    echo '<div class="container-voci">';
                    if(checkResult($gv->getVoci())){
                        $counterV = 1;
                        foreach($gv->getVoci() as $item){
                            $voce = updateToVoce($item);
                            echo '<div class="container-voce">';
                                //echo '<div class="num-progressivo sinistra"><strong>'.$counterGV.'.'.$counterV.'</strong></div>';

                                echo '<div class="destra">';                                        
                                    echo '<div class="date">';
                                        parent::printDatePickerFormField(FRM_VOCE_DLRISOLUZIONE.'-'.$voce->getID(), LBL_VOCE_DLRISOLUZIONE, false, $voce->getDataLimiteRisoluzione());
                                        parent::printDatePickerFormField(FRM_VOCE_DVULTIMAZIONE.'-'.$voce->getID(), LBL_VOCE_DVULTIMAZIONE, false, $voce->getDataVerificaUltimazione());
                                    echo '</div>';
                                    echo '<div class="stato-voce">';
                                    $this->printSemafori($voce, $isResponsabile, $obj);                                            
                                    echo '</div>';
                                echo '</div>';

                                echo '<div class="dati-voce centro">';                                        
                                    parent::printHiddenFormField(DBT_IDVOCE.'-'.$voce->getID(), $voce->getID());

                                    echo '<div class="descrizione"><strong>'.$counterV.'</strong> '.$voce->getDescrizione().'</div>'; 
                                    echo '<div class="esito">';
                                    //esito
                                    $this->printEsito($voce->getTipo(), $voce->getID(), $voce->getEsito());
                                    echo '</div>';

                                    //note                                    
                                    $this->printNote($voce->getID(), $voce->getNote());                                   

                                    echo '<div class="container-commenti">';
                                    echo '<h5>Commenti salvati</h5>';
                                        $this->printCommenti($voce->getCommenti());
                                    echo '</div>';

                                    echo '<a class="btn btn-aggiungi-commento">AGGIUNGI COMMENTO</a>';
                                    echo '<div class="clear"></div>';
                                    echo '<div class="aggiungi-commento">';
                                        $this->printAggiungiCommento($voce->getID());
                                    echo '</div>';
                                echo '</div>';

                                echo '<div class="clear"></div>';
                            echo '</div>';
                            $counterV++;
                        }
                    }
                    else{
                        echo '<p>Non sono presenti voci per questo gruppo voce</p>';
                    }                    

                    echo '</div>';
                    parent::printEndDetailsForm(FRM_GV);
                echo '</div>';
            }
            //var_dump($gv);
                
            
            
            echo '</div>';
        }
        else{
            echo '<p>Non è stata trovata alcuna voce</p>';
        }   
    }
    
    
    private function printEsito($esito, $id, $value=null){
        $result = '';
        
        switch($esito){
            case VOCE_TIPO_NESSUNA:
                break;
            case VOCE_TIPO_SINO:
                $arrayEsito = array(1 => 'No', 2 => 'Si');
                $result = parent::printSelectFormField(FRM_VOCE_ESITO.'-'.$id, 'Esito', $arrayEsito, false, $value);
                break;
            case VOCE_TIPO_TESTO:
                $result = parent::printTextFormField(FRM_VOCE_ESITO.'-'.$id, 'Esito', false, $value);
                break;
        }
        
        return $result;
    }
    
    private function printNote($idVoce, $value){
        $settings  = array( 'media_buttons' => true );
        echo '<div class="titolo-note">';
        echo 'NOTE';
        echo '</div>';
        return wp_editor($value, FRM_VOCE_NOTE.'-'.$idVoce, $settings);
    }
    
    private function printCommenti($array){
        
        //stampo i commenti 
        if(checkResult($array)){
            foreach($array as $item){
                $commento = updateToCommento($item);
                echo '<div class="commento-container">';
                $this->printCommento($commento);              
                echo '</div>';
            }
        }
        else{
            echo '<p>Non sono presenti commenti per questa voce</p>';
        }
       
    }
    
    private function printCommento(Commento $obj){
        
        if($obj->getAutore() == get_current_user_id()){
            //se l'utente corrente è anche l'autore del commento, può modificarlo           
            parent::printHiddenFormField(DBT_IDCOMMENTO, $obj->getID());
            echo '<p><strong>Data pubblicazione:</strong> '.$obj->getDataPubblicazione().'</p>';
            echo '<p><strong>Autore: </strong>'. getNiceName($obj->getAutore()).'</p>';
            echo '<div class="commento-contenuto">'.$obj->getContenuto().'</div>';           
            echo '<a data-id="'.$obj->getID().'" class="btn elimina-commento-ajax">ELIMINA COMMENTO</a>';   
            echo '<div class="clear"></div>';
        }
        else{
            //altrimenti può solo visualizzarlo
            echo '<p><strong>Data pubblicazione:</strong> '.$obj->getDataPubblicazione().'</p>';
            echo '<p><strong>Autore: </strong>'. getNiceName($obj->getAutore()).'</p>';
            echo '<div class="commento-contenuto">'.$obj->getContenuto().'</div>';
        }
    }
    
    
    private function printAggiungiCommento($idVoce){        
        echo '<h5>Aggiungi un commento</h5>';
        
        echo '<div class="id-voce">';
            parent::printHiddenFormField(FRM_COMMENTO_IDVOCE.'-'.$idVoce, $idVoce);
        echo '</div>';
        echo '<div class="id-user-wp">';
            parent::printHiddenFormField('id-user-wp-'.$idVoce, get_current_user_id());
        echo '</div>';
        echo '<div class="my-wp-editor">';
            parent::printWpEditor('', FRM_COMMENTO_CONTENUTO.'-'.$idVoce, true);
        echo '</div>';
        echo '<a class="btn salva-commento-ajax">Salva commento</a>';
        
    }
    
    
    protected function checkCommentoFields(){
        
        $commento = new Commento();
        $commento->setAutore(get_current_user_id());
        if(parent::checkSingleField(FRM_COMMENTO_CONTENUTO) !== false){
            $commento->setContenuto(parent::checkSingleField(FRM_COMMENTO_CONTENUTO));
        }
        if(parent::checkSingleField(FRM_COMMENTO_IDVOCE) !== false){
            $commento->setIdVoce(parent::checkSingleField(FRM_COMMENTO_IDVOCE));
        }
        $commento->setIdPadre(0);
        $commento->setRisposte(null);
        
        return $commento;
        
    }
    
    protected function checkBachecaFields(){
        //ottengo i valori per gruppo voci, anche se in realtà ho già gli ID delle singole voci da aggiornare e salvare
        $errors = 0;
        $result = array();
        foreach($_POST as $key => $value){
            $valore = '';
            if(strpos($key, DBT_IDVOCE.'-') !== false){
                $valore = $value;
            }
            if($valore != ''){
                //ottengo la voce per fare gli aggiornamenti
                $temp = $this->collaudo->getVoceByID($valore);
                
                if($temp !== null){
                    $voce = updateToVoce($temp);
                    if(parent::checkSingleField(FRM_VOCE_ESITO.'-'.$valore) !== false){
                        $voce->setEsito(parent::checkSingleField(FRM_VOCE_ESITO.'-'.$valore));
                    }
                    if(parent::checkSingleField(FRM_VOCE_NOTE.'-'.$valore) !== false){
                       $voce->setNote(wpautop(parent::checkSingleField(FRM_VOCE_NOTE.'-'.$valore))); 
                    }
                    if(parent::checkSingleField(FRM_VOCE_STATO.'-'.$valore) !== false){
                        $voce->setStato(parent::checkSingleField(FRM_VOCE_STATO.'-'.$valore));
                    }
                    if(parent::checkSingleField(FRM_VOCE_DLRISOLUZIONE.'-'.$valore) !== false){
                        $voce->setDataLimiteRisoluzione(parent::checkSingleField(FRM_VOCE_DLRISOLUZIONE.'-'.$valore));
                    }
                    if(parent::checkSingleField(FRM_VOCE_DVULTIMAZIONE.'-'.$valore) !== false){
                        $voce->setDataVerificaUltimazione(parent::checkSingleField(FRM_VOCE_DVULTIMAZIONE.'-'.$valore));
                    }
                }
                
                array_push($result, $voce);
                unset($voce);
            }           
        }
        
        return $result;
        
    }
    
    
    private function calcoloCompletamentoGV(GruppoVoce $obj){
        //devo calcolare i pesi per ogni voce che completa il gruppo voce
        $tot = 0;
        $completati = 0;
        if(checkResult($obj->getVoci())){
            foreach($obj->getVoci() as $item){
                $voce = updateToVoce($item);
                $tot += $voce->getPeso();
                if($voce->getStato() == VOCE_STATO_VERDE){
                    $completati += $voce->getPeso();
                }
            }
        }
        
        $percentuale = number_format((float)($completati * 100) / $tot, 0);
        
        return $completati.'/'.$tot.' ('.$percentuale.'%)';
        
    }
    
    
    private function printSemafori(Voce $voce, $isResponsabile, Collaudo $c){
        $statoAttivo = $voce->getStato();
        
        $attivoRosso = '';
        $attivoGiallo = '';
        $attivoVerde = '';
        if($statoAttivo == VOCE_STATO_ROSSO){
            $attivoRosso = 'attivo';
        }
        else if($statoAttivo == VOCE_STATO_GIALLO){
            $attivoGiallo = 'attivo';
        }
        else if($statoAttivo == VOCE_STATO_VERDE){
            $attivoVerde = 'attivo';
        }
        
        echo '<div class="semaforo">';  
        //parent::printSelectFormField(FRM_VOCE_STATO.'-'.$voce->getID(), LBL_STATO, $arrayStatoVoce, true, $voce->getStato());
        parent::printHiddenFormField(FRM_VOCE_STATO.'-'.$voce->getID(), $statoAttivo);
        if($isResponsabile){
            echo '<div class="rosso click '.$attivoRosso.'"></div>';
            echo '<div class="giallo click '.$attivoGiallo.'"></div>';
            echo '<div class="verde click '.$attivoVerde.'"></div>';
        }
        else if(isCantierista() && isCollaudatore(get_current_user_id(), $c->getID()) && $c->getTipo() == TIPO_PRECOLLAUDO ){
            //se l'utente è un cantierista e collaudatore e siamo nel precollaudo, ha la facoltà di mettere rossi i semafori
            echo '<div class="rosso click '.$attivoRosso.'"></div>';
            echo '<div class="giallo click '.$attivoGiallo.'"></div>';
            echo '<div class="verde click '.$attivoVerde.'"></div>';
        }
        else if(isCantierista()){
            if($statoAttivo != VOCE_STATO_VERDE){
                echo '<div class="rosso click '.$attivoRosso.'"></div>';
                echo '<div class="giallo click '.$attivoGiallo.'"></div>';
                echo '<div class="verde '.$attivoVerde.'"></div>';
            }
            else{
                echo '<div class="rosso '.$attivoRosso.'"></div>';
                echo '<div class="giallo '.$attivoGiallo.'"></div>';
                echo '<div class="verde '.$attivoVerde.'"></div>';
            }
        }
        else if(isCliente()){
            echo '<div class="rosso '.$attivoRosso.'"></div>';
            echo '<div class="giallo '.$attivoGiallo.'"></div>';
            echo '<div class="verde '.$attivoVerde.'"></div>';            
        }
        echo '<div class="clear"></div>';
        echo '</div>';
        //indico il valore del semaforo in modo testuale
        echo '<p style="text-align:center">Stato attuale:</p><p class="stato-semaforo"><strong>';
        if($statoAttivo == VOCE_STATO_ROSSO){
            echo 'DA VERIFICARE';
        }
        else if($statoAttivo == VOCE_STATO_GIALLO){
            echo 'IN ATTESA DI VERIFICA';
        }
        else if($statoAttivo == VOCE_STATO_VERDE){
            echo 'VERIFICATO';
        }
        echo '</strong></p>';
    }
}
