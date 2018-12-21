<?php

/*****************	WEB Options Definitions ******************/

define("SYS_TITLE",	".:: CAMI | Catálogo Maestro de Incentivos ::.");
define("SYS_URL",	"http://localhost/CAMI");
define("WEB_MAIL",	"admin@mlg.com.mx");
define('SYS_MAIL', 	'admin@mlg.com.mx');
define('PFX_SYS', 	'cm_');
define('PFX_MLG', 	'mlg_');
define('CONFIG_PATH', 	'mlg_');
define('DB_CTRL','Control.dbo.');

if( php_sapi_name() == "cli" ){
	$config_path = yaml_parse_file('/var/www/html/CAMI/config/config_path.yml');
}else{
	$config_path = yaml_parse_file('config/config_path.yml');
}
$config = yaml_parse_file($config_path['path']);

if( $_SERVER['SERVER_NAME'] == 'localhost' 
	|| $_SERVER['SERVER_NAME'] == '192.168.3.210' ){
	$env = 'dev';
}else if( $_SERVER['SERVER_NAME'] == '204.232.152.20' 
	|| php_sapi_name() == "cli" 
	|| $_SERVER['SERVER_NAME'] == '10.160.60.22'  
	|| $_SERVER['SERVER_NAME'] == 'mlg-dev.com.mx'  ){
	if( strpos($_SERVER['PHP_SELF'], 'cami_demo') === false ){
		$env = 'prod';
	}else{
		$env = 'demo';
	}
}else{
	$env = 'test';
}

if( !empty($config[$env]) ){
	/****************** Main DB Configuration *****************/
	define("DB_HOST", 		$config[$env]['db']['host']);
	define("DB_USERNAME", 	$config[$env]['db']['user']);
	define("DB_PASSWORD", 	$config[$env]['db']['pass']);
	define("DB_NAME", 		$config[$env]['db']['name']);
	define("PFX_MAIN_DB", 	$config[$env]['db']['pfix']);
}

/****************** SAI DB Configuration *****************/
define("SAI_HOST", 				'db.sai'	);
define("SAI_USERNAME", 			'UsrCatMlg'		);
define("SAI_PASSWORD", 			'CatMlg#01'		); 
define("SAI_NAME", 				'Catalogo'		); //'mlg_sai_acc_Desarrollo');
define("SAI_PFX", 				'cm_');

/****************** SAI DB Configuration *****************/
define("CTRL_HOST", 	'db.sai' );
define("CTRL_USERNAME", 'guardian' );
define("CTRL_PASSWORD", 'c4d3n3r0MLG' ); 
define("CTRL_NAME", 	'Control' );
define("CTRL_PFX", 		'ct_' );


/**************** 	Paths Definitions	 ******************/
define("DIRECTORY_CLASS",		SYS_PATH . "class/");
define("DIRECTORY_VIEWS", 		SYS_PATH . "views/");
define("DIRECTORY_BASE", 		SYS_PATH . "base/");
define("DIRECTORY_TEMPLATES",	SYS_PATH . "templates/"); 
define("DIRECTORY_UPLOADS",		SYS_PATH . "uploads/"); 
define("DIRECTORY_FUNCS",		SYS_PATH . "funcs/");  
define("DIRECTORY_AJAX",		SYS_PATH . "ajax/"); 
define("DIRECTORY_IMAGES",		SYS_PATH . "img/");  
define("DIRECTORY_INVOICE",		SYS_PATH . "invoice/"); 
define("DIRECTORY_CRON",		SYS_PATH . "cron/"); 

/************** 	Views Configuration 	****************/
$_command=1001;

/**************		LOGGING Definitions 	****************/
define('LOG_DIR', 		SYS_PATH . 'log/');
define('LOG_FILE', 		PFX_SYS . 'log');
define('LOG_TMPLT', 	'[%s] %s @ %s: %s');
define('LOG_MAX_SIZE', 	'10730000'); // 1G = 1073741824 bytes
/////////////////////////////////////////////////////////////

define('LOG_PRC_DOWN',  1);
define('LOG_TRANS_ERR', 2);
define('LOG_DB_ERR',  	3);
define('LOG_SESS_ERR',  4);
define('LOG_INFO_ERR',  5);
define('LOG_API_ERR',   6);

/**************		Appearance & templates	****************/
define('COLOR1_DEFAULT', '#518351');
define('COLOR2_DEFAULT', '#fafafa');
define('COLOR3_DEFAULT', '#454545');

define('TMPL_DATE','Y-m-d');
define('TMPL_DATE_TIME','Y-m-d H:i');
?>