<?php 
session_start();
ini_set('display_errors', TRUE);

$path = "";
if ( !isset( $path ) ){
	define("SYS_PATH", "");
} else {
	define("SYS_PATH", $path);
}
date_default_timezone_set (  'America/Mexico_City' ); 

ini_set('session.cookie_domain', str_replace("www.", "", $_SERVER['HTTP_HOST']));
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2500M');
ini_set('default_socket_timeout', 1500);

define("DIRECTORY_CONFIG", 	SYS_PATH . "config/"); 
require_once(DIRECTORY_CONFIG . 'config.php'); 
require_once(DIRECTORY_CLASS  . 'class.object.php');
require_once(DIRECTORY_CLASS  . 'class.log.php');
require_once(DIRECTORY_CLASS  . 'class.pdo_mysql.php');
require_once(DIRECTORY_CLASS  . 'class.pdo_sqlsrv.php');
require_once(DIRECTORY_CLASS  . 'class.settings.php');
require_once(DIRECTORY_CLASS  . 'class.session.php'); 
require_once(DIRECTORY_CLASS  . 'class.validate.php');
include_once(DIRECTORY_FUNCS  . 'func.php');

$Log 		= new Log( PFX_SYS . "cron_log" );
$obj_bd 	= new PDOMySQL( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
$sql_db 	= new PDOSQLServer( SAI_HOST, SAI_USERNAME, SAI_PASSWORD, "mlg_sai_acc_Desarrollo" );
$Validate 	= new Validate();
$Settings	= new Settings();

$Session 	= new Session();
$Session->user 	= "cron"; 
$Session->level = 1;
$Session->id 	= "1";

if ( !class_exists('Tracking') )
	require_once DIRECTORY_CLASS . 'class.tracking.php'; 
$Tracking = new Tracking();

require_once(DIRECTORY_CLASS  . 'class.cron_manager.php');
$CronManager = new CronManager();
$CronManager->execute();
echo "<pre>";
var_dump( $CronManager );
echo "<hr/>";
var_dump( $sql_db );
echo "</pre>";
?>