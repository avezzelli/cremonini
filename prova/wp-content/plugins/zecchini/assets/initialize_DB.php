<?php

namespace zecchini;

/*** CREAZIONE DATABASE ***/
function install_zecchini_db(){
    try{
        
        //BRAND
        $args = array(
            array(
                'nome'  => DBT_NOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_BRAND_LOGO,
                'tipo'  => 'TEXT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_IDCLIENTE,
                'tipo'  => 'INT',
                'null'  => null
            )
        );
        creaTabella(DBT_BRAND, $args);
        
        //CANTIERE
        $args = array(
            array(
                'nome'  => DBT_NOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_INDIRIZZO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ), 
            array(
                'nome'  => DBT_IDBRAND,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),     
            array(
                'nome'  => DBT_STATO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_CANTIERE_DAPERTURA,
                'tipo'  => 'TIMESTAMP',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_CANTIERE_DCHIUSURA,
                'tipo'  => 'TIMESTAMP',
                'null'  => null
            )   
        );
        $fks = array(
            array(
                'key1'      => DBT_IDBRAND,
                'tabella'   => DBT_BRAND
            )
        );        
        creaTabella(DBT_CANTIERE, $args, $fks);
        
        //COLLAUDO
        $args = array(
            array(
                'nome'  => DBT_COLLAUDO_TIPO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_NOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDCANTIERE,
                'tipo'  => 'INT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_COLLAUDO_DATA,
                'tipo'  => 'TIMESTAMP',
                'null'  => null
            ),
            array(
                'nome'  => DBT_NOTE,
                'tipo'  => 'TEXT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_COLLAUDO_BRAND,
                'tipo'  => 'TEXT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_COLLAUDO_LOCALE,
                'tipo'  => 'TEXT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_STATO,
                'tipo'  => 'INT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_COLLAUDO_PDF,
                'tipo'  => 'TEXT',
                'null'  => null
            )
        );
        creaTabella(DBT_COLLAUDO, $args);
        
        //CANTIERE / COLLAUDO
        $args = array(
            array(
                'nome'  => DBT_IDCANTIERE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDCANTIERE,
                'tabella'   => DBT_CANTIERE
            ),
            array(
                'key1'      => DBT_IDCOLLAUDO,
                'tabella'   => DBT_COLLAUDO
            )
        );
        creaTabella(DBT_CANCOL, $args, $fks);
        
        //GRUPPO VOCI
        $args = array(
            array(
                'nome'  => DBT_GV_TITOLO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_STATO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ), 
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDCOLLAUDO,
                'tabella'   => DBT_COLLAUDO
            )
        );
        creaTabella(DBT_GV, $args, $fks);
        
        //VOCE
        $args = array(
            array(
                'nome'  => DBT_VOCE_DESCRIZIONE,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),            
            array(
                'nome'  => DBT_VOCE_PESO,
                'tipo'  => 'FLOAT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_STATO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ), 
            array(
                'nome'  => DBT_VOCE_DLRISOLUZIONE,
                'tipo'  => 'TIMESTAMP',
                'null'  => null
            ), 
            array(
                'nome'  => DBT_VOCE_DVULTIMAZIONE,
                'tipo'  => 'TIMESTAMP',
                'null'  => null
            ), 
            array(
                'nome'  => DBT_IDGV,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_NOTE,
                'tipo'  => 'TEXT',
                'null'  => null
            ), 
            array(
                'nome'  => DBT_VOCE_TIPO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_VOCE_ESITO,
                'tipo'  => 'TEXT',
                'null'  => null
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDGV,
                'tabella'   => DBT_GV
            )
        );
        creaTabella(DBT_VOCE, $args, $fks);
        
        //COMMENTO
        $args = array(
            array(
                'nome'  => DBT_COMMENTO_AUTORE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_COMMENTO_CONTENUTO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_COMMENTO_DPUBBLICAZIONE,
                'tipo'  => 'TIMESTAMP',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_COMMENTO_IDPADRE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDVOCE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDVOCE,
                'tabella'   => DBT_VOCE
            )
        );
        creaTabella(DBT_COMMENTO, $args, $fks);
        
        //AZIENDA
        $args = array(
            array(
                'nome'  => DBT_AZIENDA_RSOCIALE,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_INDIRIZZO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_AZIENDA_REFERENTE,
                'tipo'  => 'TEXT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_TELEFONO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_EMAIL,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_AZIENDA_PIVA,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            )
        );
        creaTabella(DBT_AZIENDA, $args);
        
        //AZIENDA / CANTIERE
        $args = array(
            array(
                'nome'  => DBT_IDCANTIERE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDAZIENDA,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDCANTIERE,
                'tabella'   => DBT_CANTIERE
            ),
            array(
                'key1'      => DBT_IDAZIENDA,
                'tabella'   => DBT_AZIENDA
            )
        );
        creaTabella(DBT_AZICAN, $args, $fks);
        
        //UTENTE
        $args = array(            
            array(
                'nome'  => DBT_TELEFONO,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_EMAIL,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
             array(
                'nome'  => DBT_UC_UW,
                'tipo'  => 'INT',
                'null'  => null
            ),
        );
        creaTabella(DBT_UTENTE, $args);
                
        //UTENTEC
        $args = array(  
            array(
                'nome'  => DBT_NOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_COGNOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDAZIENDA,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDUTENTE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(            
            array(
                'key1'      => DBT_IDUTENTE,
                'tabella'   => DBT_UTENTE
            )
        );
        creaTabella(DBT_UC, $args, $fks);
        
        //CLIENTE
        $args = array(
            array(
                'nome'  => DBT_AZIENDA_RSOCIALE,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            ), 
            array(
                'nome'  => DBT_IDUTENTE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_AZIENDA_PIVA,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            )            
        );
        $fks = array(
            array(
                'key1'      => DBT_IDUTENTE,
                'tabella'   => DBT_UTENTE
            )            
        );
        creaTabella(DBT_CLIENTE, $args, $fks);
        
        //RUOLO
        $args = array(
            array(
                'nome'  => DBT_NOME,
                'tipo'  => 'TEXT',
                'null'  => 'NOT NULL'
            )  
        );
        creaTabella(DBT_RUOLOC, $args);
        
        //UTENTEC / RUOLOC
        $args = array(
            array(
                'nome'  => DBT_IDUTENTEC,
                'tipo'  => 'INT',
                'null'  => null
            ),
            array(
                'nome'  => DBT_IDRC,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDAZIENDA,
                'tipo'  => 'INT',
                'null'  => null
            )
        );
        /*
        $fks = array(
            array(
                'key1'      => DBT_IDUTENTE,
                'tabella'   => DBT_UC
            ),
            array(
                'key1'      => DBT_IDRC,
                'tabella'   => DBT_RUOLOC
            ),
            array(
                'key1'      => DBT_IDCOLLAUDO,
                'tabella'   => DBT_COLLAUDO
            )
        );
         * *
         * 
         */
        creaTabella(DBT_UTERUO, $args);
        
        //COLLAUDO / RUOLOC
        $args = array(
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDRC,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDCOLLAUDO,
                'tabella'   => DBT_COLLAUDO
            ),
            array(
                'key1'      => DBT_IDRC,
                'tabella'   => DBT_RUOLOC
            )
        );
        creaTabella(DBT_COLRUO, $args, $fks);
        
        
        //VISIBILITA
        $args = array(
            array(
                'nome'  => DBT_IDGV,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDRC,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        $fks = array(
            array(
                'key1'      => DBT_IDGV,
                'tabella'   => DBT_GV
            ),
            array(
                'key1'      => DBT_IDRC,
                'tabella'   => DBT_RUOLOC
            )
        );
        creaTabella(DBT_VISIBILITA, $args, $fks);
        
        //LOGS
        $args = array(
            array(
                'nome'  => DBT_IDGV,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDVOCE,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_STATO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_UC_UW,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_LOG_DATA,
                'tipo'  => 'TIMESTAMP',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )                  
        );
        creaTabella(DBT_LOG, $args);
        
        //RESPONSABILI CANTIERE
        $args = array(
            array(
                'nome'  => DBT_IDCOLLAUDO,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            ),
            array(
                'nome'  => DBT_IDUTENTEC,
                'tipo'  => 'INT',
                'null'  => 'NOT NULL'
            )
        );
        creaTabella(DBT_RESPCOLLAUDO, $args);
        
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}

function delete_zecchini_db(){
    try{
        
        /*
        dropTabella(DBT_BRAND);
        
        dropTabella(DBT_COLRUO);
        dropTabella(DBT_CANCOL);
        dropTabella(DBT_CANTIERE);
        dropTabella(DBT_COLLAUDO);
        
        dropTabella(DBT_VISIBILITA);
        
        dropTabella(DBT_COMMENTO);
        dropTabella(DBT_VOCE);
        dropTabella(DBT_GV);
        
        dropTabella(DBT_AZICAN);
        dropTabella(DBT_AZIENDA);
        
        dropTabella(DBT_UTERUO);        
        dropTabella(DBT_UC);
        dropTabella(DBT_RUOLOC);
        dropTabella(DBT_CLIENTE);
        dropTabella(DBT_UTENTE);
        
        dropTabella(DBT_LOG);
        dropTabella(DBT_RESPCOLLAUDO);
         * 
         */
        
        
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}

