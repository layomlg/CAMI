<?php

/* Default */
define("HOME", 		"dsh");
define("LOGIN",	 	"lgn");

/* Products */
define("PRODUCTS",	"prd");
define("PRODUCTS_LST",	"prd_lst");
define("PRODUCT",	"frm_prd");

/* Suppliers*/
define("SUPPLIERS",	"sups");  	// Lst
define("SUPPLIER",	"sup");		// Info
define("SUPPLIER_FRM","supf");		// Info


/** Errors **/
define("ERR_403", 	"403");
define("ERR_404", 	"404");

//Controla los permisos
$uiCommand = array(
	//Titulo
	//Archivos PHP
	//Archivos JS
	//Archivos CSS
	//Archivos AJAX
	//Archivos MODALS
);

/* TODO: get the profiles ids from DB */
$profiles = array(
	1,2,3,4,5,6,7,8,9
);

$uiCommand[LOGIN]	= array(
	array(1,2,3),
	"Iniciar Sesion",
	"frm.login.php",
	"",	"",	"", ""
);

$uiCommand[HOME] = array(
	array(1,2,3),
	"Dashboard",
	DIRECTORY_VIEWS . DIRECTORY_BASE . "dashboard.php",
	"",	"",	"", ""
); 

$uiCommand[PRODUCTS_LST] = array(
	array(1,2,3,4,5,6,7,8,9),
	"Productos",
	DIRECTORY_VIEWS . "base/lst.product.php",
	"",	"",	"", ""
); 

$uiCommand[SUPPLIERS] = array(
	array(1,2,3,4,5,6,7,8,9),
	"Proveedores",
	DIRECTORY_VIEWS . "base/lst.supplier.php",
	array("supplier.js"),	"",	"", ""
); 

$uiCommand[SUPPLIER_FRM] = array(
	array(1,2,3,4,5,6,7,8,9),
	"Edición de Proveedor",
	DIRECTORY_VIEWS . "forms/frm.supplier.php",
	array("supplier.js"),	"",	"", ""
); 

$uiCommand[PRODUCTS] = array(
	array(1,2,3,4,5,6,7,8,9),
	"Productos",
	DIRECTORY_VIEWS . "dashboards/dsh.product.php",
	"",	"",	"", ""
); 

$uiCommand[PRODUCT] = array(
	array(1,2,3,4,5,6,7,8,9),
	"Producto",
	DIRECTORY_VIEWS . "forms/frm.product.php",
	"",	"",	"", ""
);  

/* Errors */
$uiCommand[ERR_403] = array(
	array(1),
	"Error 403: Restringido",
	DIRECTORY_VIEWS."base/403.php",
	"",	"",	"", "" 
);

$uiCommand[ERR_404] = array(
	array(1),
	"Error 404: No encontrado",
	DIRECTORY_VIEWS."base/404.php",
	"",	"",	"", "" 
);

?>