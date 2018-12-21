<?php
require 'init.php';
$id  = ( isset( $_REQUEST['id'] )  && $_REQUEST['id'] != '' ) ? $_REQUEST['id'] : "";
$type= isset( $_REQUEST['t'] ) && is_numeric( $_REQUEST['t']) && $_REQUEST['t'] > 0 ? $_REQUEST['t'] : 0;
if ( $id != '' ) {
	require_once DIRECTORY_CLASS . "class.file.manager.php";
	$fmanager = new FileManager();
	$resp = $fmanager->output_file( $id, $type ); 
} else {
	require_once DIRECTORY_VIEWS . DIRECTORY_BASE . "404.php";
} 
 
die();
?>