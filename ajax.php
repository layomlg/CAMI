<?php
require_once "init.php";
ini_set('default_charset', 'utf-8');
header('Content-Type: text/html; charset=utf-8');
header('Content-Encoding: utf-8');
header('Connection: Keep-Alive');
//ini_set('display_errors', true);
try {

	$response 	= array( 'success' => false );
	$resource	= isset($_REQUEST['resource']) ? $_REQUEST['resource'] : '';
	$action 	= isset($_REQUEST['action']	) ? $_REQUEST['action'] : '';
	
	switch ( $resource ){ 
		case 'impute':
		case 'canje':   
		case 'catalogue':  	
		case 'lists': 
		case 'meta': 	
		case 'oic':    
		case 'settings': 
		case 'dashboard': 
		case 'tracking': 
			require_once DIRECTORY_AJAX . 'ajax.' . $resource . '.php';
			break;  
		case 'user':
		case 'profile':
			require_once DIRECTORY_AJAX . 'ajax.admin.php';
			break;  
		case 'admin.catalogues': 
				global $Session;
			if ( $Session->is_admin() ){
				require_once DIRECTORY_AJAX . 'ajax.' . $resource . '.php';
			} else {
				global $Log;
				$Log->write_log( "Restricted access ", SES_RESTRICTED_ACTION, 3 ); 
				$response['error'] = "Restricted access";
			}
			break;
		default:  
			$response['error'] = "Invalid resource";
			break; 
	} 	
} catch ( Exception $err ){ 
	$response 	= array( 'success' => false, 'error' => $err->__toString() );
}
echo json_encode( $response );
?>