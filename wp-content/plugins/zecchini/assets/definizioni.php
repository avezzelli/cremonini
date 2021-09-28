<?php

namespace zecchini;

/************************ DEFINIZIONI **************************/
define('DB_PREFIX', 'wp_zecchini_');
define('ELEMENTI_PER_PAGINA', 10);

//ruoli utenti
define('RUOLO_ADMIN', 'administrator');
define('RUOLO_CLIENTE', 'cliente');
define('RUOLO_CANTIERISTA', 'utentec');
define('RUOLO_AZIENDA', 'azienda');

//Tipi
define('TIPO_PRECOLLAUDO', 1);
define('TIPO_COLLAUDO', 2);

//Tipo voce
define('VOCE_TIPO_NESSUNA', 1);
define('VOCE_TIPO_SINO', 2);
define('VOCE_TIPO_TESTO', 3);

//Stato voce
define('VOCE_STATO_ROSSO', 1);
define('VOCE_STATO_GIALLO', 2);
define('VOCE_STATO_VERDE', 3);

//Stato cantiere
define('CANTIERE_STATO_APERTO', 1);
define('CANTIERE_STATO_CHIUSO', 2);

//STATO COLLAUDO
define('COLLAUDO_STATO_DAINIZIARE', 1);
define('COLLAUDO_STATO_INCORSO', 2);
define('COLLAUDO_STATO_COMPLETATO', 3);

//AZIENDA APPROVATO
define('AZIENDA_APPROVATO_NO', 1);
define('AZIENDA_APPROVATO_SI', 2);

/************************ OGGETTI **************************/
define('OBJ_BRAND', 'brand');
define('OBJ_CANTIERE', 'cantiere');
define('OBJ_COLLAUDO', 'collaudo');
define('OBJ_CANCOLL', 'cantiere_collaudo');
define('OBJ_GV', 'gruppovoci');
define('OBJ_VOCE', 'voce');
define('OBJ_COMMENTO', 'commento');
define('OBJ_AZIENDA', 'azienda');
define('OBJ_AZICAN', 'azienda_cantiere');
define('OBJ_UTENTE', 'utente');
define('OBJ_CLIENTE', 'cliente');
define('OBJ_UTENTEC', 'utentecantiere');
define('OBJ_RUOLOC', 'ruolocantiere');
define('OBJ_UTERUO', 'utentec_ruoloc');
define('OBJ_VISIBILITA', 'visibilita');
define('OBJ_CLIETNE', 'cliente');
define('OBJ_COLRUO', 'collaudo_ruoloc');
define('OBJ_LOG', 'log');
define('OBJ_RESPCOLLAUDO', 'responsabile_collaudo');

/************************ TABELLE DATABASE **************************/
//nomi comuni
define('DBT_NOME', 'nome');
define('DBT_COGNOME', 'cognome');
define('DBT_INDIRIZZO', 'indirizzo');
define('DBT_STATO', 'stato');
define('DBT_TELEFONO', 'telefono');
define('DBT_IDAZIENDA', 'id_azienda');
define('DBT_EMAIL', 'email');
define('DBT_NOTE', 'note');

//ID
define('DBT_IDCLIENTE', 'id_cliente');
define('DBT_IDCANTIERE', 'id_cantiere');
define('DBT_IDUTENTEC', 'id_utentec');
define('DBT_IDRC', 'id_ruoloc');
define('DBT_IDGV', 'id_gruppovoci');
define('DBT_IDCOLLAUDO', 'id_collaudo');
define('DBT_IDVOCE', 'id_voce');
define('DBT_IDUTENTE', 'id_utente');
define('DBT_IDBRAND', 'id_brand');
define('DBT_IDCOMMENTO', 'id_commento');

//Brand
define('DBT_BRAND', 'brands');
define('DBT_BRAND_LOGO', 'logo');

//Cantiere
define('DBT_CANTIERE', 'cantieri');
define('DBT_CANTIERE_DAPERTURA', 'data_apertura');
define('DBT_CANTIERE_DCHIUSURA', 'data_chiusura');

//Collaudo
define('DBT_COLLAUDO', 'collaudi');
define('DBT_COLLAUDO_TIPO', 'tipo');
define('DBT_COLLAUDO_DATA', 'data_collaudo');
define('DBT_COLLAUDO_BRAND', 'brand');
define('DBT_COLLAUDO_LOCALE', 'locale');
define('DBT_COLLAUDO_PDF', 'url_pdf');

//Cantiere / Collaudo
define('DBT_CANCOL', 'cantiere_collaudo');

//Gruppo Voci
define('DBT_GV', 'gruppo_voci');
define('DBT_GV_TITOLO', 'titolo');

//Voce
define('DBT_VOCE', 'voci');
define('DBT_VOCE_DESCRIZIONE', 'descrizione');
define('DBT_VOCE_PESO', 'peso');
define('DBT_VOCE_DLRISOLUZIONE', 'data_limite_risoluzione');
define('DBT_VOCE_DVULTIMAZIONE', 'data_verifica_ultimazione');
define('DBT_VOCE_TIPO', 'tipo');
define('DBT_VOCE_ESITO', 'esito');

//Commento
define('DBT_COMMENTO', 'commenti');
define('DBT_COMMENTO_AUTORE', 'autore');
define('DBT_COMMENTO_CONTENUTO', 'contenuto');
define('DBT_COMMENTO_DPUBBLICAZIONE', 'data_pubblicazione');
define('DBT_COMMENTO_IDPADRE', 'id_padre');

//Azienda
define('DBT_AZIENDA', 'aziende');
define('DBT_AZIENDA_RSOCIALE', 'ragione_sociale');
define('DBT_AZIENDA_REFERENTE', 'referente');
define('DBT_AZIENDA_PIVA', 'partita_iva');
define('DBT_AZIENDA_APPROVATO', 'approvato');

//Utente
define('DBT_UTENTE', 'utente');

//Cliente
define('DBT_CLIENTE', 'cliente');

//UtenteC
define('DBT_UC', 'utentic');
define('DBT_UC_UW', 'utente_wp');

//Azienda / Cantiere
define('DBT_AZICAN', 'azienda_cantiere');

//RuoloC
define('DBT_RUOLOC', 'ruolic');

//UtenteC / RuoloC
define('DBT_UTERUO', 'utentec_ruoloc');

//Collaudo / RuoloC
define('DBT_COLRUO', 'collaudo_ruoloc');

//Visibilita
define('DBT_VISIBILITA', 'visibilita');

//LOG
define('DBT_LOG', 'logs');
define('DBT_LOG_DATA', 'data_operazione');

//RESPONSABILE COLLAUDO
define('DBT_RESPCOLLAUDO', 'responsabili_collaudo');
        
        
/********************************* FORM *********************************/
define('FRM_ID', 'id');

//Azienda
define('FRM_AZIENDA', 'azienda');
define('FRM_AZIENDA_RAGIONES', FRM_AZIENDA.'-ragionesociale');
define('FRM_AZIENDA_INDIRIZZO', FRM_AZIENDA.'-indirizzo');
define('FRM_AZIENDA_REFERENTE', FRM_AZIENDA.'-referente');
define('FRM_AZIENDA_TELEFONO', FRM_AZIENDA.'-telefono');
define('FRM_AZIENDA_EMAIL', FRM_AZIENDA.'-email');
define('FRM_AZIENDA_PIVA', FRM_AZIENDA.'-piva');
define('FRM_AZIENDA_APPROVATO', FRM_AZIENDA.'-approvato' );
define('FRM_AZIENDA_PASSWORD', FRM_AZIENDA.'-password');

//Brand
define('FRM_BRAND', 'brand');
define('FRM_BRAND_CLIENTE', FRM_BRAND.'-cliente');
define('FRM_BRAND_NOME', FRM_BRAND.'-nome');
define('FRM_BRAND_LOGO', FRM_BRAND.'-logo');

//Cantiere
define('FRM_CANTIERE', 'cantiere');
define('FRM_CANTIERE_NOME', FRM_CANTIERE.'-nome');
define('FRM_CANTIERE_INDIRIZZO', FRM_CANTIERE.'-indirizzo');
define('FRM_CANTIERE_BRAND', FRM_CANTIERE.'-brand');
define('FRM_CANTIERE_PRECOLLAUDO', FRM_CANTIERE.'-precollaudo');
define('FRM_CANTIERE_COLLAUDO', FRM_CANTIERE.'-collaudo');
define('FRM_CANTIERE_AZIENDE', FRM_CANTIERE.'-aziende');
define('FRM_CANTIERE_STATO', FRM_CANTIERE.'-stato');
define('FRM_CANTIERE_DAPERTURA', FRM_CANTIERE.'-dataapertura');
define('FRM_CANTIERE_DCHIUSURA', FRM_CANTIERE.'-datachiusura');

//Collaudo
define('FRM_COLLAUDO', 'collaudo');
define('FRM_COLLAUDO_TIPO', FRM_COLLAUDO.'-tipo');
define('FRM_COLLAUDO_NOME', FRM_COLLAUDO.'-nome');
define('FRM_COLLAUDO_DATA', FRM_COLLAUDO.'-datacollaudo');
define('FRM_COLLAUDO_NOTE', FRM_COLLAUDO.'-note');
define('FRM_COLLAUDO_BRAND', FRM_COLLAUDO.'-brand');
define('FRM_COLLAUDO_LOCALE', FRM_COLLAUDO.'-locale');
define('FRM_COLLAUDO_IDCANTIERE', FRM_COLLAUDO.'-idcantiere');
define('FRM_COLLAUDO_STATO', FRM_COLLAUDO.'-stato');
define('FRM_COLLAUDO_RESPONSABILI', FRM_COLLAUDO.'-responsabili');

//Cliente
define('FRM_CLIENTE', 'cliente');
define('FRM_CLIENTE_RS', FRM_CLIENTE.'-ragionesociale');
define('FRM_CLIENTE_TELEFONO', FRM_CLIENTE.'-telefono');
define('FRM_CLIENTE_EMAIL', FRM_CLIENTE.'-email');
define('FRM_CLIENTE_PASS', FRM_CLIENTE.'-password');
define('FRM_CLIENTE_PIVA', FRM_CLIENTE.'-piva');

//UtenteC
define('FRM_UTENTEC', 'utentec');
define('FRM_UTENTEC_COGNOME', FRM_UTENTEC.'-cognome');
define('FRM_UTENTEC_NOME', FRM_UTENTEC.'-nome');
define('FRM_UTENTEC_EMAIL', FRM_UTENTEC.'-email');
define('FRM_UTENTEC_TELEFONO', FRM_UTENTEC.'-telefono');
define('FRM_UTENTEC_PASS', FRM_UTENTEC.'-password');

//Ruoli
define('FRM_RUOLO', 'ruolo');
define('FRM_RUOLO_NOME', FRM_RUOLO.'-nome');

//Gruppo Voce
define('FRM_GV', 'gv');
define('FRM_GV_TITOLO', FRM_GV.'-titolo');
define('FRM_GV_STATO', FRM_GV.'-stato');
define('FRM_GV_VISIBILITA', FRM_GV.'-visibilita');

//Voce
define('FRM_VOCE', 'voce');
define('FRM_VOCE_DESCRIZIONE', FRM_VOCE.'-descrizione');
define('FRM_VOCE_PESO', FRM_VOCE.'-peso');
define('FRM_VOCE_STATO', FRM_VOCE.'-stato');
define('FRM_VOCE_DLRISOLUZIONE', FRM_VOCE.'-dlrisoluzione');
define('FRM_VOCE_DVULTIMAZIONE', FRM_VOCE.'-dvultimazione');
define('FRM_VOCE_NOTE', FRM_VOCE.'-note');
define('FRM_VOCE_TIPO', FRM_VOCE.'-tipo');
define('FRM_VOCE_ESITO', FRM_VOCE.'-esito');

//Commento
define('FRM_COMMENTO', 'commento');
define('FRM_COMMENTO_CONTENUTO', FRM_COMMENTO.'-contenuto');
define('FRM_COMMENTO_IDVOCE', FRM_COMMENTO.'-idvoce');



/************************ LABEL **************************/

//nomi comuni
define('LBL_ID', 'ID');
define('LBL_NOME', 'Nome');
define('LBL_TIPO', 'Tipo');
define('LBL_COGNOME', 'Cognome');
define('LBL_EMAIL', 'E-mail');
define('LBL_TELEFONO', 'Telefono');
define('LBL_RAGIONES', 'Ragione Sociale');
define('LBL_PASSWORD', 'Password');
define('LBL_PIVA', 'Partita IVA');
define('LBL_NOTE', 'Note');
define('LBL_TITOLO', 'Titolo');
define('LBL_STATO', 'Stato');
define('LBL_VISIBILITA', 'Visibilità');

//Azienda
define('LBL_AZIENDA', 'Azienda');
define('LBL_AZIENDA_INDIRIZZO', 'Indirizzo');
define('LBL_AZIENDA_REFERENTE', 'Referente');
define('LBL_AZIENDA_APPROVATO', 'Approvato');

//Brand
define('LBL_BRAND', 'Brand');
define('LBL_BRAND_LOGO', 'Logo');

//Cantiere
define('LBL_CANTIERE', 'Cantiere');
define('LBL_CANTIERE_NOME', 'Identificativo');
define('LBL_CANTIERE_INDIRIZZO', 'Indirizzo');
define('LBL_CANTIERE_BRAND', 'Brand');
define('LBL_CANTIERE_PRECOLLAUDO', 'Precollaudo');
define('LBL_CANTIERE_COLLAUDO', 'Collaudo');
define('LBL_CANTIERE_AZIENDE', 'Aziende');
define('LBL_CANTIERE_DAPERTURA', 'Data apertura');
define('LBL_CANTIERE_DCHIUSURA', 'Data chiusura');

//Collaudo
define('LBL_COLLAUDO', 'Collaudo');
define('LBL_COLLAUDO_DATA', 'Data collaudo');
define('LBL_COLLAUDO_BRAND', 'Tipologia locale');
define('LBL_COLLAUDO_LOCALE', 'Identificativo locale');
define('LBL_COLLAUDO_RESPONSABILI', 'Responsabili collaudo');

//Cliente
define('LBL_CLIENTE', 'Proprietario');

//UtenteC
define('LBL_UTENTEC', 'Referente');

//Ruolo
define('LBL_RUOLO', 'Ruolo');

//Gruppo Voci
define('LBL_GV', 'Gruppo voce');

//Voce
define('LBL_VOCE', 'Voce');
define('LBL_VOCE_DESCRIZIONE', 'Descrizione');
define('LBL_VOCE_PESO', 'Peso');
define('LBL_VOCE_DLRISOLUZIONE', 'Data limite risoluzione');
define('LBL_VOCE_DVULTIMAZIONE', 'Data verifica ultimazione');
define('LBL_VOCE_ESITO', 'Esito');


/************************ ARRAY **************************/
