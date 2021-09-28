<?php
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via
 * web, è anche possibile copiare questo file in «wp-config.php» e
 * riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Prefisso Tabella
 * * Chiavi Segrete
 * * ABSPATH
 *
 * È possibile trovare ulteriori informazioni visitando la pagina del Codex:
 *
 * @link https://codex.wordpress.org/it:Modificare_wp-config.php
 *
 * È possibile ottenere le impostazioni per MySQL dal proprio fornitore di hosting.
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define( 'DB_NAME', 'zecchini' );

/** Nome utente del database MySQL */
define( 'DB_USER', 'root' );

/** Password del database MySQL */
define( 'DB_PASSWORD', '' );

/** Hostname MySQL  */
define( 'DB_HOST', 'localhost' );

/** Charset del Database da utilizzare nella creazione delle tabelle. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');

/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '03tR[msPE?<dgI3^|lzMCm<f@Pt;JJfxc *Pl2OE5Y:gYFr:XI8ej]>Y-Ce.|7kp' );
define( 'SECURE_AUTH_KEY',  '.$JX=9Z:+h4l(/gfu*`(o4168WK`:A4tW2V2Y:5~iy+}N%JYgE> z~kO!<]Hh<E=' );
define( 'LOGGED_IN_KEY',    'CEKBKz|$.y8VX[_q^`GW:_AC8B:~4yg_W@*uYm5Gel6Uq!Gsa,c}x^.QEOdVti_+' );
define( 'NONCE_KEY',        'yHd1#Yza,dRHLSkU[z@K2=v-(,h>t%*uI<{ZL<xHZe{0 r=V7Rt@[*l{/;J_0rl,' );
define( 'AUTH_SALT',        'aM1dHI0_meJKQ><a.GZPvG-nGs:zn-_I)qU(vQs)]z#(X.jAa&h6.LUAQZ)%,;{(' );
define( 'SECURE_AUTH_SALT', 'a!Ypk4 _^o!Uq!r.d$4~r.Xe=8anNMgHHLQyu`QB%uK(Qt}>fC{U3;9Hs(}?c;Sm' );
define( 'LOGGED_IN_SALT',   'b r4Zb@3ng)B65HIXND?|/(,hyUB(PE5:RESkycV.#mO-WGS8W}CT4rEhQD8CXR{' );
define( 'NONCE_SALT',       '*:iGmFr>;PJ?-XE?Mg4o>V}4myYwQ3UpXYuS#}4P9Rvw&t;H%`aj5F4E]Ci;=_Ns' );

/**#@-*/

/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix = 'wp_';

/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi
 * durante lo sviluppo.
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true );
/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');
