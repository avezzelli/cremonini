<?php

namespace zecchini;

/**** FUNZIONI COMUNI ******/

function curPageURL() {
    $pageURL = 'http'; 
    
    /*
    if ($_SERVER["HTTPS"] == "on") { 
        $pageURL .= "s"; 
    } 
     * 
     */  

    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
     $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
     $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/** CREAZIONE PAGINE ***/
function createPage($titolo, $contenuto, $parent=null){
    $args = array(
        'post_title'    => $titolo,
        'post_content'  => $contenuto,
        'post_type'     => 'page',
        'post_status' => 'publish',
    );
    
    if($parent != null){
        $page = get_page_by_title($parent, OBJECT, 'page');
        $args = array(
            'post_title'    => $titolo,
            'post_content'  => $contenuto,
            'post_type'     => 'page',
            'post_status'   => 'publish',
            'post_parent'   => $page->ID
        );
    }
    
      
    return wp_insert_post($args);
            
}

function printShortcodes($pagine){
    //la funzione scrive un file php dove all'interno ci sono gli shortcode delle pagine create
    
    try{       
        //apro il file
        $file = fopen(PATH_ZECCHINI.'shortcodes.php', 'w') or die('Error!');
        $txt = "<?php \n\n";
        $txt.= '/********** SHORTCODE *************/';
        $txt.= "\n";
        
        foreach($pagine as $item){
            $txt.= '//'.$item['pagina'];
            $txt.="\n";
            $txt.="add_shortcode('".$item['url']."Inserimento', 'addPage".$item['pagina']."Inserimento');\n";
            $txt.="function addPage".$item['pagina']."Inserimento(){\n";
            $txt.="\tinclude 'pages/".$item['url']."/save.php';\n";
            $txt.="}\n";
            $txt.="add_shortcode('".$item['url']."Dettaglio', 'addPage".$item['pagina']."Dettaglio');\n";
            $txt.="function addPage".$item['pagina']."Dettaglio(){\n";
            $txt.="\tinclude 'pages/".$item['url']."/details.php';\n";
            $txt.="}\n";
            $txt.="add_shortcode('".$item['url']."Search', 'addPage".$item['pagina']."Search');\n";
            $txt.="function addPage".$item['pagina']."Search(){\n";
            $txt.="\tinclude 'pages/".$item['url']."/search.php';\n";
            $txt.="}\n\n";
        }
        
        $txt.= "\n?>";
        fwrite($file, $txt);
        fclose($file);
        
    } catch (Exception $ex) {
        var_dump($ex);
    }    
}

function createAllPages($pagine){
    //1. creare un array con i nomi
    //2. ciclare l'array e creare le pagine    
        
    foreach($pagine as $item){
        //pagina padre
        if(post_exists($item['pagina']) == 0){
            createPage($item['pagina'], '');
        }
        //inserimento
        if(post_exists('Inserimento '.$item['pagina']) == 0){
            createPage('Inserimento '.$item['pagina'], '['.$item['url'].'Inserimento]', $item['pagina']);
        }        
        //dettaglio
        if(post_exists('Dettaglio '.$item['pagina']) == 0){
            createPage('Dettaglio '.$item['pagina'], '['.$item['url'].'Dettaglio]', $item['pagina']);
        }
        //search
        if(post_exists('Ricerca '.$item['pagina']) == 0){
            createPage('Ricerca '.$item['pagina'], '['.$item['url'].'Search]', $item['pagina']);
        }
    }    
}

function updateTo($type, MyObject $o){
    $result = null;
    switch($type){
        case OBJ_BRAND:
            $result = new Brand();
            break;
        case OBJ_CANTIERE:
            $result = new Cantiere();
            break;
        case OBJ_COLLAUDO:
            $result = new Collaudo();
            break;
        case OBJ_CANCOLL:
            $result = new CantiereCollaudo();
            break;
        case OBJ_GV:
            $result = new GruppoVoce();
            break;
        case OBJ_VOCE:
            $result = new Voce();
            break;
        case OBJ_COMMENTO:
            $result = new Commento();
            break;
        case OBJ_AZIENDA:
            $result = new Azienda();
            break;
        case OBJ_AZICAN:
            $result = new AziendaCantiere();
            break;
        case OBJ_UTENTE:
            $result = new Utente();
            break;
        case OBJ_CLIENTE:
            $result = new Cliente();
            break;
        case OBJ_RUOLOC:
            $result = new RuoloC();
            break;
        case OBJ_UTERUO:
            $result = new UtentecRuoloc();
            break;
        case OBJ_VISIBILITA:
            $result = new Visibilita();
            break;
        case OBJ_COLRUO:
            $result = new CollaudoRuoloc();
            break;
        case OBJ_LOG:
            $result = new Log();
            break;
        case OBJ_RESPCOLLAUDO:
            $result = new ResponsabileCollaudo();
            break;
    }    
    $result = $o;
    return $result;
}

function updateToBrand(\zecchini\MyObject $o): \zecchini\Brand{
    return updateTo(OBJ_BRAND, $o);
}
function updateToCantiere(\zecchini\MyObject $o): \zecchini\Cantiere{
    return updateTo(OBJ_CANTIERE, $o);
}
function updateToCollaudo(\zecchini\MyObject $o): \zecchini\Collaudo{
    return updateTo(OBJ_COLLAUDO, $o);
}
function updateToCanCol(\zecchini\MyObject $o): \zecchini\CantiereCollaudo{
    return updateTo(OBJ_CANCOLL, $o);
}
function updateToGV(\zecchini\MyObject $o): \zecchini\GruppoVoce{
    return updateTo(OBJ_GV, $o);
}
function updateToVoce(\zecchini\MyObject $o): \zecchini\Voce{
    return updateTo(OBJ_VOCE, $o);
}
function updateToCommento(\zecchini\MyObject $o): \zecchini\Commento{
    return updateTo(OBJ_COMMENTO, $o);
}
function updateToAzienda(\zecchini\MyObject $o): \zecchini\Azienda{
    return updateTo(OBJ_AZIENDA, $o);
}
function updateToAziCan(\zecchini\MyObject $o): \zecchini\AziendaCantiere{
    return updateTo(OBJ_AZICAN, $o);
}
function updateToUtente(\zecchini\MyObject $o): \zecchini\Utente{
    return updateTo(OBJ_UTENTE, $o);
}
function updateToUtenteC(\zecchini\MyObject $o): \zecchini\UtenteC{
    return updateTo(OBJ_UTENTEC, $o);
}
function updateToCliente(\zecchini\MyObject $o): \zecchini\Cliente{
    return updateTo(OBJ_CLIENTE, $o);
}
function updateToRuoloC(\zecchini\MyObject $o): \zecchini\RuoloC{
    return updateTo(OBJ_RUOLOC, $o);
}
function updateToUteRuo(\zecchini\MyObject $o): \zecchini\UtentecRuoloc{
    return updateTo(OBJ_UTERUO, $o);
}
function updateToVisibilita(\zecchini\MyObject $o): \zecchini\Visibilita{
    return updateTo(OBJ_VISIBILITA, $o);
}
function updateToColRuo(\zecchini\MyObject $o): \zecchini\CollaudoRuoloc{
    return updateTo(OBJ_COLRUO, $o);
}
function updateToLog(\zecchini\MyObject $o): \zecchini\Log{
    return updateTo(OBJ_LOG, $o);
}
function updateToRespCollaudo(\zecchini\MyObject $o): \zecchini\ResponsabileCollaudo{
    return updateTo(OBJ_RESPCOLLAUDO, $o);
}

function translateToTimestamp($date){
    $result = null;
    if($date != null && $date != ''){
        $temp = explode('/', $date);
        if(count($temp) > 0){
            $result = $temp[2].'-'.$temp[1].'-'.$temp[0];
        }
    }
    return $result;
}

function convertToTimestamp($date){
    $temp1 = str_replace('-', '', $date);
    $temp2 = str_replace('/', '-', $temp1);
    return strtotime($temp2);
}

function translateToDate($time, $data = false){
    if($time != null && $time != ''){        
        $temp = explode(' ', $time);
        $time1 = explode('-', $temp[0]);
        $time2 = explode(':', $temp[1]);

        if($data == false){    
            return $time1[2].'/'.$time1[1].'/'.$time1[0];
        }
        return $time1[2].'/'.$time1[1].'/'.$time1[0].' - '.$temp[1];
    }
    return '';
}

function checkResult($temp){
    if($temp != null && count($temp) > 0){
        return true;
    }
    return false;
}

/** FUNZIONI DI SCAMBIO TRA CLASSI **/
function getAllRuoli(){
    $utente = new UtenteController();
    return $utente->getAllRuoli();
}


//Funzioni comuni sui ruoli

function getRoleUser(){
    if(is_user_logged_in()){
        $user = wp_get_current_user();
        return (array) $user->roles;
    }
    else{
        return false;
    }
}

function isAdmin(){
    $roles = getRoleUser();    
    if($roles !== false){
        foreach($roles as $item){
           if($item == RUOLO_ADMIN){
               return true;
           }
        }
    }    
    return false;
}

function isCliente(){
    $roles = getRoleUser();    
    if($roles !== false){
        foreach($roles as $item){
           if($item == RUOLO_CLIENTE){
               return true;
           }
        }
    }    
    return false;
}

function isCantierista(){
    $roles = getRoleUser();    
    if($roles !== false){
        foreach($roles as $item){
           if($item == RUOLO_CANTIERISTA){
               return true;
           }
        }
    }    
    return false;
}

function isAzienda(){
    $roles = getRoleUser(); 
    if($roles !== false){
        foreach($roles as $item){
            if($item == RUOLO_AZIENDA){
                return true;
            }
        }
    }
    return false;
}

/*** ALTRE FUNZIONI ***/
function getNomeCliente($idCliente){
    $utente = new UtenteController();
    if($idCliente != null){
        $cliente = updateToCliente($utente->getClienteByID($idCliente));
        return $cliente->getRagioneSociale();
    }
    return '-';
}

function clearSearchBox(){
    if(isset($_POST[FRM_SEARCH_RESET])){            
        unset($_POST);            
    }
}

/**
 * la funzione restituisce un array di ruoli collaudo passato un idcollaudo
 * @param type $idCollaudo
 * @return type
 */
function getRuoliByIdCollaudo($idCollaudo){
    $controller = new CollaudoController();
    return $controller->getCollaudiRuoloByIdCollaudo($idCollaudo);
}

function getRuoloByID($idRuolo){
    $controller = new UtenteController();
    return $controller->getRuoloByID($idRuolo);
}

function getCollaudo($idCollaudo){
    $controller = new CollaudoController();
    return $controller->getCollaudoByID($idCollaudo);
}

function getBrandsForSelectBox(){
    $result = array();
    $controller = new BrandController();
    $temp = $controller->getAllBrands();
    if(checkResult($temp)){
        foreach($temp as $item){
            $brand = updateToBrand($item);
            $result[$brand->getID()] = $brand->getNome();
        }
    }
    
    return $result;
}

function getIdUtenteByIdWP($idWP){
    $controller = new UtenteController();
    return $controller->getUtenteByIdWP($idWP);    
}

/**
 * La funzione restituisce un id utente assegnato ad un ruolo in un collaudo
 * @param type $idRuoloc
 * @param type $idCollaudo
 * @return type
 */
function getIdUtente($idRuoloc, $idCollaudo){
    $urDAO = new UtentecRuolocDAO();
    $where = array(
        array(
            'campo'     => DBT_IDRC,
            'valore'    => $idRuoloc,
            'formato'   => 'INT'
        ),
        array(
            'campo'     => DBT_IDCOLLAUDO,
            'valore'    => $idCollaudo,
            'formato'   => 'INT'
        )
    );
    $temp = $urDAO->getUtentiRuoli($where);
    if($temp != null && count($temp) == 1){
        $ur = updateToUteRuo($temp[0]);
        if($ur->getIdUtenteC() != null){
            return 'utentec-'.$ur->getIdUtenteC();
        }
        else{
            return 'azienda-'.$ur->getIdAzienda();
        }
        
        
    }
    return null;
}

function getTitoloGruppoVoce($idGV){
    //la funzione deve restituire il nome del gruppo voce passato l'id
    $collaudo = new CollaudoController();
    $temp = $collaudo->getGruppoVoceByID($idGV);
    $gv = updateToGV($temp);
    return $gv->getTitolo();
}

function getTitoloVoce($idVoce){    
    $collaudo = new CollaudoController();
    $temp = $collaudo->getVoceByID($idVoce);
    if($temp != null){
        //può capitare che la voce non ci sia
        $voce = updateToVoce($temp);
        return $voce->getDescrizione();
    }
    return $idVoce;
}

function getNiceName($idUtenteWP){
    $user_info = get_userdata($idUtenteWP);
    $result = '';
    if($user_info->user_lastname != '' || $user_info->user_firstname != ''){
        $result = $user_info->user_lastname.' '.$user_info->user_firstname;
    }
    else{
        $result = $user_info->user_login;
    }
    
    return $result;
}

function canReadGV(GruppoVoce $gv, $idUserWP, $isResponsabile){        
    //se è amministratore o responsabile, può vedere    
    if(isAdmin() || $isResponsabile == true){
        return true;
    }    
    //devo ottenere l'id utentec    
    $ur = new UtentecRuolocDAO();    
    $idUtenteC = getIdUtenteC($idUserWP);      
    if($idUtenteC != null){           
        //var_dump($gv);
        //ottengo la visibilià del gruppo voce
        //ciò che ottengo sono gli id dei ruoliC che possono vedere il gruppo voce 
        $arrayV = $gv->getVisibilita();        
        foreach($arrayV as $item){            
            //faccio un controllo incrociato sulla tabella utentec_ruoloc dove cerco id_utentec, id_collaudo e id_ruoloc
            //se la query mi restituisce un risultato allora l'utente è abilitato a vedere, altrimenti no
            $where = array(
                array(
                    'campo'     => DBT_IDCOLLAUDO,
                    'valore'    => $gv->getIdCollaudo(),
                    'formato'   => 'INT'
                ),
                array(
                    'campo'     => DBT_IDUTENTEC,
                    'valore'    => $idUtenteC,
                    'formato'   => 'INT'
                ),
                array(
                    'campo'     => DBT_IDRC,
                    'valore'    => $item,
                    'formato'   => 'INT'
                )
            );
            $temp = $ur->getUtentiRuoli($where);            
            if(count($temp) > 0){
                return true;
            }           
        }        
    }    
    return false;
}

function getIdUtenteC($idUserWP){
    $utenteController = new UtenteController();
    $utente = $utenteController->getUtenteByIdWP($idUserWP);    
    if(isset($utente['utentecantiere'])){
        $utentec = updateToUtenteC($utente['utentecantiere']);         
        return $utentec->getID() ;
    }
    return null;
}

function isResponsabile($responsabili, $idUserWP){
    if(isAdmin()){
        return true;
    }
    
    $idUtenteC = getIdUtenteC($idUserWP);
    
    if($idUtenteC != null){
        foreach($responsabili as $item){
            if($idUtenteC == $item){
                return true;
            }
        }
    }
    return false;
}

function isCollaudatore($idUserWP, $idCollaudo){
    $utente = new UtenteController();
    if(isAdmin()){
        return true;
    }
     
    $idUtenteC = getIdUtenteC($idUserWP);
    //controllo i collaudatori del collaudo
    $collaudatori = $utente->getUtenteCByRuoloCollaudo('collaudatore', $idCollaudo);    
    if(checkResult($collaudatori)){
        foreach($collaudatori as $item){
            if($idUtenteC == $item){
                return true;
            }
        }
    }
    
    return false;
}

function printBachecaAdmin(){
    //la bacheca dell'admin deve mostrare tutti i cantieri attivi, suddivisi per 
    $brandC = new BrandController();
    $brands = $brandC->getAllBrands();
    $cantiereV = new CantiereView();
    
    
    if(checkResult($brands)){
        
        //stampo i tab
        echo '<ul id="navBrand" class="nav nav-tabs" role="tablist">';
        $count = 1;
        foreach($brands as $item){
            $brand = updateToBrand($item);
            $active = '';
            if($count == 1){
                $active = 'active';               
            }            
            echo '<li role="presentation" class="'.$active.'">';
            echo '<a href="#brand-'.$brand->getID().'" aria-controls="brand" role="tab" data-toggle="tab">'.$brand->getNome().'</a>';
            echo '</li>';            
            $count++;
        }
        echo '</ul>';
                
        //stampo i box
        echo '<div class="tab-content container-brand-tab">';
        $count = 1;        
        foreach($brands as $item){
            $brand = updateToBrand($item);
            $active = '';
            if($count == 1){
                $active = 'active';               
            } 
            
            echo '<div role="tabpanel" class="tab-pane '.$active.'" id="brand-'.$brand->getID().'">';
            
            $cantiereV->printCantieriList($brand->getID());
            
            echo '</div>';
            
            $count++;
        }
        
        echo '</div>';
        
    }
    else{
        echo '<p>Nessun Brand presente nel sistema.</p>';
    }
}

function printBachecaAzienda(){
    $controller = new AziendaController();
    $azienda = updateToAzienda($controller->getAziendaByIdWP(get_current_user_id()));
    if($azienda->getApprovato() == AZIENDA_APPROVATO_NO){
        echo '<p>Il tuo account deve essere approvato da un amministratore</p>';
    }
    else if($azienda->getApprovato() == AZIENDA_APPROVATO_SI){
                
        $aziendaView = new AziendaView();
        $aziendaView->listenerDetailsForm();
        $aziendaView->listenerCantieristaSaveForm();
        $aziendaView->printDetailsForm($azienda->getID());
    }
}

function printBachecaCantierista(){
    $cBrand = new BrandController();
    $cCantiere = new CantiereController();
    $result = getBrandByUtenteWP(get_current_user_id());
    
    
    if(checkResult($result)){    
        echo '<p style="padding-left:15px">Ecco la lista dei cantieri assegnati</p>';
        foreach($result as $idBrand => $cantieri){
            echo '<div class="container-brands bacheca-cantierista">';
            $brand = updateToBrand($cBrand->getBrandByID($idBrand));
            //stampo una tabella con il brand a sinistra e i cantieri a destra
            echo '<div class="brand left">';
            echo ' <img src="'.$brand->getLogo().'"><br>';
            //echo ' <strong>'.$brand->getNome().'</strong>';
            echo '</div>';
            //a sinistra invece l'elenco dei cantieri con la possibilità di collegarsi alla bacheca
            echo '<div class="cantieri right">';
            foreach($cantieri as $item){
                echo '<div class="cantiere">';
                $cantiere = $cCantiere->getCantiere($item);
                echo '<strong>'.$cantiere->getNome().'</strong><br>';
                echo '<p>'.$cantiere->getIndirizzo().'</p>';
                echo '<p><strong>Data apertura: </strong>'.$cantiere->getDataApertura().'</p>';

                echo '<div class="collaudi">';
                //controllo il precollaudo
                $pc = updateToCollaudo($cantiere->getPrecollaudo());            
                $isInPreCollaudo = isUtenteinCollaudo(get_current_user_id(), $pc->getID());                
                
                if($isInPreCollaudo > 0){
                    PrinterView::printButtonUrl2('Vedi Bacheca Precollaudo', 'bacheca-'.FRM_COLLAUDO.'/?ID='.$pc->getID() );
                    if($isInPreCollaudo > 1 ){
                        echo '<strong>Completamento: </strong>'.calcolaCompletamentoCollaudo($pc);
                    }
                }
                //controllo il collaudo
                $co = updateToCollaudo($cantiere->getCollaudo());
                $isInCollaudo = isUtenteinCollaudo(get_current_user_id(), $co->getID());
                
                if($isInCollaudo > 0){
                    PrinterView::printButtonUrl2('Vedi Bacheca Collaudo', 'bacheca-'.FRM_COLLAUDO.'/?ID='.$co->getID() );
                    if($isInCollaudo > 1){
                        echo '<strong>Completamento: </strong>'.calcolaCompletamentoCollaudo($co);
                    }
                }            
                echo '</div>';
                echo '</div>';

                //var_dump($cantiere);            
            }
            echo '</div>';
            echo '<div class="clear"></div>';
            echo '</div>';
        }
    }
    else{
        echo '<p>Nessun cantiere assegnato</p>';
    }    
}

function isUtenteinCollaudo($idWP, $idCollaudo){
    
    $result = 0;
    //ottengo l'idUtenteC
    $idUtenteC = getIdUtenteC($idWP);
    
    //l'utente è abilitato a visualizzare il collaudo se fa match con i ruoli
    $urDAO = new UtentecRuolocDAO();
    $where = array(
        array(
            'campo'     => DBT_IDUTENTEC,
            'valore'    => $idUtenteC,
            'formato'   => 'INT'
        ),
        array(
            'campo'     => DBT_IDCOLLAUDO,
            'valore'    => $idCollaudo,
            'formato'   => 'INT'
        )
    );    
    $temp = $urDAO->getUtentiRuoli($where);
    if(count($temp) > 0){
        //ho trovato la corrispondenza --> assegno 1 se non ha già trovato nulla
        if($result < 1){
            $result = 1;
        }
    }
    
    //oppure se è un responsabile del collaudo
    $resDAO = new ResponsabileCollaudoDAO();
    $where2 = array(
        array(
            'campo'     => DBT_IDUTENTEC,
            'valore'    => $idUtenteC,
            'formato'   => 'INT'
        ),
        array(
            'campo'     => DBT_IDCOLLAUDO,
            'valore'    => $idCollaudo,
            'formato'   => 'INT'
        )
    );
    $temp2 = $resDAO->getResponsabiliCollaudo($where2);   
    if(count($temp2) > 0){
        //ho trovato la corrispondenza --> assegno 2 se questo è responsabile e scavalca il controllo precedente
        if($result < 2){
            $result = 2;
        }
    }
    
    return $result;
}


function getIdUtenteCByUtenteWP($IdWP){
    //1. Da IdWP ottengo l'ID Utente (dbt_utente)
    $uDAO = new UtenteDAO();
    $where1 = array(
        array(
            'campo'     => DBT_UC_UW,
            'valore'    => $IdWP,
            'formato'   => 'INT'
        ),
    );
    $temp1 = $uDAO->getResults($where1);
    $idUtente = null;
    if($temp1 != null && count($temp1) == 1){
        $utente = updateToUtente($temp1[0]);
        $idUtente = $utente->getID();
    }
    else{
        return null;
    }
    
    //2. da idUtente ottengo idUtenteC (dbt_utentec)
    $idUtenteC = null;
    if($idUtente != null){
        $ucDAO = new UtenteCDAO();
        $where2 = array(
            array(
                    'campo'     => DBT_IDUTENTE,
                    'valore'    => $idUtente,
                    'formato'   => 'INT'
            ),
        );
        $temp2 = $ucDAO->getResults($where2);
        if($temp1 != null && count($temp2) == 1){
            $utenteC = updateToUtenteC($temp2[0]);
            $idUtenteC = $utenteC->getID();
        }
    }
    else{
        return null;
    }
    
    return $idUtenteC;
}

/**
 * Ottengo i brand assegnati ad un utente
 * @param type $IdWP
 * @return type
 */
function getBrandByUtenteWP($IdWP){
    $result= array();
    $brands = array();
    $collaudi = array();
    $cantieri = array();
    
    $idUtenteC = getIdUtenteC($IdWP);
    
    //3. da idUtenteC ottengo idCollaudi (DBT_UTERUO)
    $collaudi = getCollaudiByUtenteC($idUtenteC);    
    
    //4. da idcollaudi ottengo idcantieri (dbt_collaudo)
    $cantieri = getCantieriByCollaudi($collaudi);
    
    //5. da idCantiere ottengo idBrand (dbt_cantiere)
    $result = getBrandByCantieri($cantieri, $result);
            
    return $result;
    
    
}

function getBrandByCantieri($cantieri, $result){
    if(checkResult($cantieri)){
        $caDAO = new CantiereDAO();
        foreach($cantieri as $item){
            $temp5 = $caDAO->getResultByID($item);
            $cantiere = updateToCantiere($temp5);            
            if($cantiere->getStato() == CANTIERE_STATO_APERTO){
                if(!isset($result[$cantiere->getIdBrand()])){
                    $result[$cantiere->getIdBrand()] = array();
                }
                array_push($result[$cantiere->getIdBrand()], $cantiere->getID());     
            }
        }
        
        //elimino i duplicati
        foreach($result as $idBrand => $cantieri){
            $result[$idBrand] = array_unique($cantieri);
        }        
    }
    return $result;
}

function getCantieriByCollaudi($collaudi){
    $cantieri = array();
    if(checkResult($collaudi)){
        $coDAO = new CollaudoDAO();
        foreach($collaudi as $item){ 
            $temp4 = $coDAO->getResultByID($item);
            $collaudo = updateToCollaudo($temp4);
            if($collaudo->getStato() != COLLAUDO_STATO_COMPLETATO){
                array_push($cantieri, $collaudo->getIdCantiere());
            }
        }
    }    
    return $cantieri;
}

function getCollaudiByUtenteC($idUtenteC){
    $collaudi = array();
    
    if($idUtenteC != null){
        
        //posso ottenere i collaudi se l'utente è assegnato ad un ruolo
        $urDAO = new UtentecRuolocDAO();
        $where3 = array(
            array(
                'campo'     => DBT_IDUTENTEC,
                'valore'    => $idUtenteC,
                'formato'   => 'INT'
            ),
        );
        $temp3 = $urDAO->getUtentiRuoli($where3);
        if(checkResult($temp3)){
            foreach($temp3 as $item){
                $ur = updateToUteRuo($item);
                array_push($collaudi, $ur->getIdCollaudo());
            }
        }
        
        //oppure se è responsabile di un collaudo (e non avere ruoli assegnati)
        $resDAO = new ResponsabileCollaudoDAO();
        $where4 = array(
            array(
                'campo'     => DBT_IDUTENTEC,
                'valore'    => $idUtenteC,
                'formato'   => 'INT'
            ),
        );
        $temp4 = $resDAO->getResponsabiliCollaudo($where4);
        if(checkResult($temp4)){
            foreach($temp4 as $item){
                $res = updateToRespCollaudo($item);
                array_push($collaudi, $res->getIdCollaudo());
            }
        }        
        //elimino i duplicati
        $collaudi = array_unique($collaudi);        
    }
    
    return $collaudi;
}


function getCantiere($idCantiere) : Cantiere{
    $controller = new CantiereController();
    return $controller->getCantiere($idCantiere);
}

function getBrand($idBrand) : Brand{
    $controller = new BrandController();
    return $controller->getBrandByID($idBrand);
}

function getNomeResponsabile($idRuoloc, $idCollaudo){
    $result = '';
    $urDAO = new UtentecRuolocDAO();
    $utente = new UtenteController();
    $azienda = new AziendaController();
    $where = array(
        array(
            'campo'     => DBT_IDCOLLAUDO,
            'valore'    => $idCollaudo,
            'formato'   => 'INT'
        ),
         array(
            'campo'     => DBT_IDRC,
            'valore'    => $idRuoloc,
            'formato'   => 'INT'
        )
    );
    
    $temp = $urDAO->getUtentiRuoli($where);
    //i campi inseriti dovrebbero dare un solo valore trovato
    if($temp != null && count($temp) == 1){
        $ur = updateToUteRuo($temp[0]);
        
        if($ur->getIdUtenteC() != null){
            $utenteC = updateToUtenteC($utente->getUtenteCById($ur->getIdUtenteC()));
            $result = $utenteC->getCognome().' '.$utenteC->getNome();
        }
        else if($ur->getIdAzienda() != null){
            $a = updateToAzienda($azienda->getAziendaByID($ur->getIdAzienda()));
            $result = $a->getRagioneSociale();
        }
    }    
    return $result;
}

function getStatoVoce($stato){
    $result = '';
    switch($stato){
        case VOCE_STATO_ROSSO:
            $result = 'DA VERIFICARE';
            break;
        case VOCE_STATO_GIALLO:
            $result = 'IN ATTESA DI VERIFICA';
            break;
        case VOCE_STATO_VERDE:
            $result = 'VERIFICATO';                 
    }    
    return $result;
}

function calcolaCompletamentoCollaudo(Collaudo $collaudo, $intero=null){
    //devo calcolare i completamento per tutti i gruppi voce
    $countTot = 0;
    $countFatte = 0;
    foreach($collaudo->getGruppoVoci() as $item){
        $gv = updateToGV($item);
        foreach($gv->getVoci() as $item2){
            $voce = updateToVoce($item2);
            $countTot += $voce->getPeso();
            if($voce->getStato() == VOCE_STATO_VERDE){
                $countFatte+= $voce->getPeso();
            }
        }
    }
    if($countFatte != 0){
        if($intero == null){
            return number_format((float)($countFatte * 100) / $countTot, 0).' %';
        }
        else{
            return number_format((float)($countFatte * 100) / $countTot, 0);
        }
    }
    if($intero == null){
        return '0 %';
    }
    else{
        return 0;
    }
    
}

/************* FUNZIONI AJAX ***********/
function salvaCommento(){
    
    $contenuto = $_POST['contenuto'];
    $idVoce = $_POST['idVoce'];
    $idWP = $_POST['idWP'];
    
    $commento = new Commento();
    $commento->setAutore($idWP);
    $commento->setContenuto($contenuto);
    $commento->setIdVoce($idVoce);
    $commento->setIdPadre(0);
    $commento->setRisposte(null);
    
    $controller = new CollaudoController();
    if(!$controller->saveCommento($commento)){
        echo false;
    }
    echo true;
    
    die();
}

function eliminaCommento(){
    $idCommento = $_POST['idCommento'];
    $controller = new CollaudoController();
    $controller->deleteCommento($idCommento);
    echo true;
    die();
}

/**
 * La funzione è asincrona e copia i gruppi voce dal precollaudo associato. * 
 */
function copiaPrecollaudo(){
    $idCollaudo = $_POST['idCollaudo'];
    $idPrecollaudo = $_POST['idPrecollaudo'];
    
    $controller = new CollaudoController();
    
    //devo ottenere il precollaudo dall'ID
    $preCollaudo = $controller->getCollaudoByID($idPrecollaudo);
    //print_r($preCollaudo);
    
    //ottengo il collaudo dall'ID
    $collaudo = $controller->getCollaudoByID($idCollaudo);
    //print_r($collaudo);
    
    //copio i gruppi voce del precollaudo e li metto nel collaudo
    $gvs = $preCollaudo->getGruppoVoci();
    //print_r($gvs);
    
    //MODIFICA: nasce la necessità di copiare anche i ruoli del precollaudo e assegnarli (fin dove si può) al collaudo
    $arrayRuoliPrecollaudo = array();
    foreach($preCollaudo->getRuoli() as $item){
        $ruolo = updateToRuoloC($item);
        $arrayRuoliPrecollaudo[$ruolo->getID()] = getIdUtente($ruolo->getID(), $preCollaudo->getID());        
    }    
    $arrayRuoliCollaudo = array();
    foreach($collaudo->getRuoli() as $item){
        $ruolo = updateToRuoloC($item);
        array_push($arrayRuoliCollaudo, $ruolo->getID());
    }        
    $ruoli = copiaRuoliDaPrecollaudo($arrayRuoliPrecollaudo, $arrayRuoliCollaudo, $collaudo->getID());
    $copy = $controller->copiaRuoli($collaudo->getID(), $ruoli);    
           
    $save = $controller->copiaGruppiVoceDaPrecollaudo($gvs, $idCollaudo);
    
    if($save == true ){
        echo true;
    } 
    else{
        echo false;
    }    
    die();
}

function copiaRuoliDaPrecollaudo($arrayRuoliPrecollaudo, $arrayRuoliCollaudo, $idCollaudo){
    $result = array();
    foreach($arrayRuoliCollaudo as $item){
        if(isset($arrayRuoliPrecollaudo[$item])){
            //controllo se si stratta di un utenteC o azienda
            $temp = explode('-', $arrayRuoliPrecollaudo[$item]);
            if($temp != null && count($temp) > 0){
                $ur = new UtentecRuoloc();
                $ur->setIdCollaudo($idCollaudo);
                $ur->setIdRuoloC($item);                
                if($temp[0] == 'utentec'){
                    $ur->setIdUtenteC($temp[1]);
                    $ur->setIdAzienda(null);
                }
                else if($temp[0] == 'azienda'){
                    $ur->setIdAzienda($temp[1]);
                    $ur->setIdUtenteC(null);
                }
                array_push($result, $ur);
                unset($ur);
            }
        }
    }
    return $result;
}

function generaPDF(){
    $controller = new CollaudoController();    
    $idCollaudo = $_POST['idCollaudo'];
    
    //ottengo un oggetto collaudo
    $c = updateToCollaudo($controller->getCollaudoByID($idCollaudo));
    //creo ed ottengo l'url del pdf
    $c->setPdf($controller->generaPDF($c));
   
    //aggiorno il collaudo
    $controller->updateCollaudo($c);
    unset($_POST);
    
    echo true;
    
    die();
    
}


function generaPDF2($idCollaudo){
    $controller = new CollaudoController();   
    $c = updateToCollaudo($controller->getCollaudoByID($idCollaudo));
    //creo ed ottengo l'url del pdf
    $c->setPdf($controller->generaPDF($c));
   
    //aggiorno il collaudo
    $controller->updateCollaudo($c);
    return true;
}


function printBenvenuto(){
    $user_info = get_userdata(get_current_user_id());
    
    echo '<h3>Benvenuto '.$user_info->user_firstname.'</h3>';
}

function aggiornaVoce(){
    
    $voceDAO = new VoceDAO();

    //devo aggiornare una voce conoscendone i parametri
    $idVoce = $_POST['idVoce'];
    $descrizione = $_POST['descrizione'];
    $peso = $_POST['peso'];
    $tipo = $_POST['tipo'];
    
    $voce = updateToVoce($voceDAO->getResultByID($idVoce));
    //setto i campi da impostare
    $voce->setDescrizione($descrizione);
    $voce->setPeso($peso);
    $voce->setTipo($tipo);
        
    //aggiorno
    if($voceDAO->update($voce) != null){
        echo true;
    }
    else{
        echo false;
    }    
    die();
}

function aggiornaVisibilita(){
    $controller = new CollaudoController();
    //ottengo i parametri
    $idGV = $_POST['idgv'];
    $visibilita = $_POST['visibilita'];        
    $update = $controller->aggiornaVisibilita($idGV, $visibilita);    
    if($update){
        echo true;
    }
    else{
        echo false;
    }        
    die();
}