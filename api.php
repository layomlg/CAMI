<?php 
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)){
	$_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}
try {
	require_once 'init.php'; 
	require_once 'class.addrvalidator.api.php'; 
	$API = new AddrValidatorApi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']); 
	echo $API->processAPI();
} catch (Exception $e) {
	echo json_encode(Array('success' => FALSE,  'code' => 500, 'error' => $e->getMessage()) );
}
?>