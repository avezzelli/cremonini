<?php

namespace zecchini;

define('DBT_ID', 'ID');

/* * ******************* CREATE TABLES ****************** */

/**
 * Funzione personalizzata per creare tabelle nel database
 * @global type $wpdb
 * @param type $tabella, indica il nome della tabella da creare
 * @param type $param, indica una serie di parametri che popolano gli attributi
 * @param type $fks, indica una serie di collegamenti a chiave esterna
 * @return boolean
 */
function creaTabella($tabella, $param, $fks = null) {
    global $wpdb;
    $charset_collate = "";
    //prefisso --> pps = plugin preventivi serrature
    $wpdb->prefix = DB_PREFIX;
    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }
    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $table = $wpdb->prefix . $tabella;
    $query = "CREATE TABLE IF NOT EXISTS $table (";
    $query .= DBT_ID." INT NOT NULL auto_increment PRIMARY KEY,";

    $counter = 0;
    foreach ($param as $p) {
        $query .= " " . $p['nome'] . " " . $p['tipo'];
        if (isset($p['null'])) {
            $query .= " " . $p['null'];
        }

        if ($counter == count($param) - 1) {
            
        } else {
            $query .= ",";
        }
        $counter++;
    }

    if ($fks != null) {
        $counter = 0;
        $query .= ',';
        foreach ($fks as $fk) {
            $query .= " FOREIGN KEY (" . $fk['key1'] . ") REFERENCES " . $wpdb->prefix . $fk['tabella'] . "(".DBT_ID.")";
            if ($counter == count($fks) - 1) {
                
            } else {
                $query .= ",";
            }
            $counter++;
        }
    }

    $query .= ");{$charset_collate}";

    try {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($query);
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}

/* * ******************* DROP TABLES ****************** */

/**
 * Funzione personalizzata per droppare tabelle dal database
 * @global type $wpdb  
 * @param type $tabella
 * @return boolean
 */
function dropTabella($tabella) {
    global $wpdb;
    $wpdb->prefix = DB_PREFIX;
    try {
        $query = "DROP TABLE IF EXISTS " . $wpdb->prefix . $tabella . ";";
        $wpdb->query($query);
        return true;
    } catch (Exception $ex) {
        _e($ex);
        return false;
    }
}

?>