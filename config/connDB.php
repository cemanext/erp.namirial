<?php
session_start();
ini_set('display_errors', '0');
//error_reporting(E_ALL & ~E_NOTICE);

/* VERSIONE */
define("VERSIONE", "v2.1.0 (".phpversion().")");
define("COPYRIGHT", "CEMA NEXT Srl");
define("LAST_UPDATE", date("d-m-Y"));

/* SERVER GESTIONALE */
define("BASE_URL", (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME']."" : "http://".$_SERVER['SERVER_NAME']."");
define("BASE_ROOT", $_SERVER['DOCUMENT_ROOT']."/");
define('ERP_DOMAIN_NAME', 'http://cndl.erp.com');

define( 'DB_HOST', '127.0.0.0' ); // set database host
define( 'DB_USER', 'root' ); // set database user

define( 'DB_PASS', 'root' ); // set database password
define( 'DB_NAME', 'namirial' ); // set database name

define('DURATA_CORSO', "190"); //6 MESI IN GIORNI
define('DURATA_CORSO_INGEGNERI', "190"); //6 MESI IN GIORNI
define('DURATA_ABBONAMENTO', "370"); //12 MESI IN GIORNI
define('DURATA_PASSWORD_UTENTE', "190"); //6 MESI IN GIORNI

define('SUFFISSO_CODICE_CLIENTE', "CNDL"); //BI - BetaImprese

/** CONFIGURAZIONE MOODLE DI BETAIMPRESE **/
define('MOODLE_DOMAIN_NAME', 'http://cndl.com');
define('MOODLE_DB_NAME', 'namirial');
define('MOODLE_TOKEN', 'e5acc897d7ffa026ad8b7afa770871ae');

/** CONFIGURAZIONE SITO WORDPRESS DI BETAIMPRESE **/
define('WP_DOMAIN_NAME', 'http://www.cndl.com');
define('WP_DB_NAME', 'cndl_wp');


define( 'SEND_ERRORS_TO', '' ); //set email notification email address
define( 'DISPLAY_DEBUG', FALSE ); //display db errors?
require_once( BASE_ROOT.'classi/class.db.php' );

//NUOVA CONNESSIONE DATABASE VIA CLASSE
global $dblink;
$dblink = DB::getInstance();

define( 'COLORE_PRIMARIO', 'blue' );
define( 'COLORE_PRIMARIO_FONT', 'font-blue' );
define( 'COLORE_PRIMARIO_FONT_BACKGROUND', 'bg-blue bg-font-blue' );
define( 'COLORE_PRIMARIO_FONT_BORDER', 'border-blue' );

define('LOG_DEBUG_ALL', true ); //STAMPA AVVISO, OK ed ERRORE - False: Stampa solo ERRORE
require_once( BASE_ROOT.'classi/class.log.php' );
$log = new logerp();

define('EMAIL_DEBUG', FALSE);
define("EMAIL_TO_SEND_DEBUG", "supporto@cemanext.it");

?>
