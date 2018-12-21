<?php
require 'init.php'; 

include_once(DIRECTORY_CLASS.'class.login.php');

$login 	= new Login(); 
$user	= isset($_POST["login_email"]) 		? strip_tags(trim($_POST["login_email"]))		: "";
$pass	= isset($_POST["login_pass"]) 	? strip_tags(trim($_POST["login_pass"])) 	: "";
$error 	= false;

if( empty($user) || empty($pass) ){
	$location = "index.php?cmd=" . LOGIN_BADLOGIN . "&err=".htmlentities("Favor de llenar los campos");
    $error = true;
}

if( $error == false ){

	$login_ctrl = $login->log_in( $user, $pass );

    if(  $login_ctrl['success'] == true ) {
		$location =  "index.php";
    }else {
        $location = "index.php?cmd="
        .LOGIN_BADLOGIN
        ."&err="
        .$login_ctrl['msg'];
    }
}else{
    $location = "index.php?cmd=" . LOGIN_BADLOGIN."&err=".htmlentities("Falta información para el ingreso.");
}

$_SESSION["cookie_http_vars"] = $http_vars;
header("HTTP/1.1 302 Moved Temporarily");
header("Location: $location");
