<?php
session_start();
if (!isset($_GET['debug'])){
	ini_set('display_errors',  0);
}else{
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
}
 
if ( !isset( $path ) ){
	define("SYS_PATH", "");
} else {
	define("SYS_PATH", $path);
}

date_default_timezone_set( 'America/Mexico_City' );
ini_set('session.cookie_domain', str_replace("www.", "", $_SERVER['HTTP_HOST']));
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2500M');
ini_set('default_socket_timeout', 3000);  
  
define("DIRECTORY_CONFIG", 		SYS_PATH . "config/"); 
require_once(DIRECTORY_CONFIG . 'config.php');
require_once(DIRECTORY_CONFIG . 'config_error.php');
require_once(DIRECTORY_CONFIG . 'config_views.php');
require_once(DIRECTORY_CONFIG . 'config_error.php');
 
require_once(DIRECTORY_CLASS  . 'class.object.php');
require_once(DIRECTORY_CLASS  . 'class.log.php');
require_once(DIRECTORY_CLASS  . 'class.pdo_mysql.php');
require_once(DIRECTORY_CLASS  . 'class.pdo_sqlsrv.php');
require_once(DIRECTORY_CLASS  . 'class.settings.php');
require_once(DIRECTORY_CLASS  . 'class.session.php');
require_once(DIRECTORY_CLASS  . 'class.index.php'); 

$Log 		= new Log();

$obj_db 	= new PDOSQLServer( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME ); 

$Settings	= new Settings();
$Session 	= new Session();

$Index		= new Index();

require_once(DIRECTORY_CLASS  . 'class.validate.php');
$Validate 	= new Validate();

require_once(DIRECTORY_CLASS . 'class.catalogue.php');
$catalogue	= new Catalogue();

require_once(DIRECTORY_CLASS  . 'class.datatable.php');
require_once(DIRECTORY_CLASS  . 'class.sqlsrv_datatable.php');
?>