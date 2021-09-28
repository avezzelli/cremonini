<?php

/**
 * @package zecchini
 */
/*
  Plugin Name: Gestionale Zecchini
  Plugin URI:
  Description: Plugin WP per la gestione del lavoro di Zecchini Associati
  Version: 1.0
  Author: Alex Vezzelli - Seo Siti Marketing
  Author URI: https//www.seositimarketing.it/
  License: GPLv2 or later
 */


//includo le librerie
require_once 'assets/definizioni.php';
require_once 'assets/api_db.php';
require_once 'assets/interfaces/interfaces.php';
require_once 'assets/classes/classes.php';
require_once 'assets/initialize_DB.php';
require_once 'assets/functions.php';
require_once(ABSPATH.'wp-admin/includes/user.php');

//indico la cartella dove è contenuto il plugin
require_once (dirname(__FILE__) . '/zecchini.php');

define('PATH_ZECCHINI', plugin_dir_path(__FILE__));

//creo il db al momento dell'installazione
register_activation_hook(__FILE__, 'install_db_zecchini');
function install_db_zecchini(){
    
    \zecchini\install_zecchini_db();
    
    $pagine = array(        
        array(
            'pagina'    => LBL_CLIENTE,
            'url'       => FRM_CLIENTE
        ),
        array(
            'pagina'    => LBL_UTENTEC,
            'url'       => FRM_UTENTEC
        ),
        array(
            'pagina'    => LBL_AZIENDA,
            'url'       => FRM_AZIENDA
        ),
        array(
            'pagina'    => LBL_BRAND,
            'url'       => FRM_BRAND
        ),
        array(
            'pagina'    => LBL_COLLAUDO,
            'url'       => FRM_COLLAUDO
        ),
        array(
            'pagina'    => LBL_CANTIERE,
            'url'       => FRM_CANTIERE
        )        
    );
    //Creo le pagine
    \zecchini\createAllPages($pagine);
    
    //Genero gli shortcode
    \zecchini\printShortcodes($pagine);
}

//rimuovo il db quando disattivo il plugin
register_deactivation_hook(__FILE__, 'remove_db_zecchini');
function remove_db_zecchini(){
    \zecchini\delete_zecchini_db();
}

/**** INSERISCO GLI SCRIPT CSS e JS ****/
//CSS
function register_public_zecchini_style(){
    wp_register_style('zecchini_style_css', plugins_url('css/style.css', __FILE__));
    wp_register_style('zecchini_bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    wp_register_style('zecchini_file-input', plugins_url('css/fileinput.min.css', __FILE__) );
    wp_register_style('zecchini_multiple', plugins_url('css/multiple-select.css', __FILE__) );
    wp_register_style('zecchini_chosen', plugins_url('css/chosen.css', __FILE__) );
    wp_register_style('zecchini_datepicker', plugins_url('css/bootstrap-datepicker.min.css', __FILE__) );
    
    wp_enqueue_style('zecchini_style_css');
    wp_enqueue_style('zecchini_bootstrap-style');
    wp_enqueue_style('zecchini_file-input');
    wp_enqueue_style('zecchini_multiple');
    wp_enqueue_style('zecchini_chosen');
    wp_enqueue_style('zecchini_datepicker');
    
}
//Registro i file css
add_action( 'wp_enqueue_scripts', 'register_public_zecchini_style' );


//JS
function register_public_zecchini_js(){
    //wp_register_script('autocomplete-js', plugins_url('gpc/js/jquery.autocomplete-min.js'), array('jquery'), '1.0', false);   
    //wp_register_script('jquery', plugins_url('gpc/js/jquery-2.0.3.min.js'), array('jquery'), '1.0', false);
    wp_register_script('ui-widget-js', plugins_url('zecchini/js/jquery-ui.min.js'), array('jquery'), '1.0', false);       
    wp_register_script('file-input', plugins_url('zecchini/js/fileinput.min.js'), array('jquery'), '1.0', false); 
    wp_register_script('livequery', plugins_url('zecchini/js/jquery.livequery.js'), array('jquery'), '1.0', false);       
    wp_register_script('script', plugins_url('zecchini/js/script.js'), array('jquery'), '1.0', false);   
    wp_register_script('multiple-select', plugins_url('zecchini/js/multiple-select.js'), array('jquery'), '1.0', false);
    wp_register_script('chosen', plugins_url('zecchini/js/chosen.jquery.min.js'), array('jquery'), '1.0', false); 
    wp_register_script('bootstrap-min', plugins_url('zecchini/js/bootstrap.min.js'), array('jquery'), '1.0', false); 
    wp_register_script('datepicker', plugins_url('zecchini/js/bootstrap-datepicker.min.js'), array('jquery'), '1.0', false); 
    wp_register_script('datepicker-it', plugins_url('zecchini/js/bootstrap-datepicker.it.min.js'), array('jquery'), '1.0', false);     
    
    //wp_enqueue_script('autocomplete-js');  
    //wp_enqueue_script('jquery'); 
    wp_enqueue_script('ui-widget-js'); 
    wp_enqueue_script('file-input'); 
    wp_enqueue_script('livequery');
    wp_enqueue_script('script'); 
    wp_enqueue_script('multiple-select'); 
    wp_enqueue_script('chosen');
    wp_enqueue_script('bootstrap-min');
    wp_enqueue_script('datepicker');
    wp_enqueue_script('datepicker-it');     
    
    //chiamate ajax
    wp_localize_script('script', 'myscript', array(
            'ajax_url'  => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('zecchini-etichette')
    ));
}
//Aggiungo il file di Javascript al plugin
add_action( 'wp_enqueue_scripts', 'register_public_zecchini_js' );

/********** SHORTCODE *************/
require_once 'shortcodes.php';

add_shortcode('printBachecaCollaudo', 'addPageBachecaCollaudo');
function addPageBachecaCollaudo(){
    include 'pages/bacheca/bacheca-collaudo.php';
}

add_shortcode('printBachecaUtente', 'addPageBachecaUtente');
function addPageBachecaUtente(){
    include 'pages/bacheca/bacheca-utente.php';
}

/********** FUNZIONI AJAX ************/
function salva_commento_ajax(){
    zecchini\salvaCommento();
}
add_action('wp_ajax_salva_commento', 'salva_commento_ajax');
add_action('wp_ajax_nopriv_salva_commento', 'salva_commento_ajax');

function elimina_commento_ajax(){
    zecchini\eliminaCommento();
}
add_action('wp_ajax_elimina_commento', 'elimina_commento_ajax');
add_action('wp_ajax_nopriv_elimina_commento', 'elimina_commento_ajax');

function copia_precollaudo_ajax(){    
    zecchini\copiaPrecollaudo();
}
add_action('wp_ajax_copia_precollaudo', 'copia_precollaudo_ajax');
add_action('wp_ajax_nopriv_copia_precollaudo', 'copia_precollaudo_ajax');


function genera_pdf_ajax(){
    zecchini\generaPDF();
}

add_action('wp_ajax_genera_pdf', 'genera_pdf_ajax');
add_action('wp_ajax_nopriv_genera_pdf', 'genera_pdf_ajax');



