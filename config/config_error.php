<?php 
/**************** 	Errors Definitions	 ****************/
$error_num = 0;
define("LOGIN_BADLOGIN",  		$error_num++);
define("LOGIN_DBFAILURE", 		$error_num++);
define("LOGIN_SUCCESS", 		$error_num++);

$error_num = 100;
define("ERR_DB_CONN",			$error_num++);
define("ERR_DB_EXEC",			$error_num++);
define("ERR_DB_QRY",			$error_num++);
define("ERR_DB_NOT_FOUND",		$error_num++);

$error_num = 200;
define("ERR_AD_CONN",			$error_num++); 
define("ERR_AD_QRY",			$error_num++); 

$error_num = 300;
define("SES_RESTRICTED_ACTION", $error_num++);
define("SES_RESTRICTED_ACCESS", $error_num++);
define("SES_INVALID_ACTION", 	$error_num++);
define("SES_INVALID_ACCESS", 	$error_num++);

$error_num = 400;
define("ERR_FILE_INVALID",		$error_num++);
define("ERR_FILE_UPLOAD",		$error_num++);
define("ERR_FILE_PERMISSION",	$error_num++);
define("ERR_FILE_NOT_FOUND",	$error_num++);

$error_num = 500;
define("ERR_API_SESSION",		$error_num++);
define("ERR_API_CALL",			$error_num++);
define("ERR_API_INVALID",		$error_num++);
define("ERR_API_VALUE",			$error_num++);
define("ERR_API_NOT_FOUND",		$error_num++);

// Validation
$error_num = 600;
define("ERR_VAL_EMPTY",			$error_num++);
define("ERR_VAL_INVALID",		$error_num++);
define("ERR_VAL_NOT_UNIQUE",	$error_num++);
define("ERR_VAL_NOT_INT",		$error_num++);
define("ERR_VAL_NOT_DATE",		$error_num++);
define("ERR_VAL_NOT_EMAIL",		$error_num++); 
define("ERR_VAL_NOT_NUMBER",	$error_num++); 

$error_num = 700;
define("ERR_CRON_EXEC",			$error_num++);
?>