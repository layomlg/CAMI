<?php
require 'init.php';
$action = ( isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL );
if ($action != NULL) {
      switch ($action) {   
		case 'lst_admin_users':  
			require_once DIRECTORY_CLASS . "class.admin.lst.php";
			$list = new AdminList( $action, $table_id );
			break;   
		case 'lst_impute':
		case 'lst_impute_historic':
			require_once DIRECTORY_CLASS . "class.sai.lst.php";
			$list = new SAIList( $action );
		case 'lst_collection': 
		case 'lst_delivery': 
		case 'lst_operator': 
		case 'lst_departure':
		case 'lst_cancelled':  
			require_once DIRECTORY_CLASS . "class.dashboard.php";
			$dash = new Dashboard( );
			$list = $dash->list; 
			break;
		default:
			echo "Invalid list.";
			die();
			break; 
	} 
 
	if (isset($_GET['filterIdx']) && $_GET['filterIdx'] != '' && isset($_GET['filterVal']) && $_GET['filterVal'] != '') {
		$list->set_filter($_GET['filterIdx'], $_GET['filterVal']);
		$list->fidx = $_GET['filterIdx'];
		$list->fval = $_GET['filterVal'];
	} 
	if (isset($_GET['extraFilterIdx']) && $_GET['extraFilterIdx'] != '' && isset($_GET['extraFilterVal']) && $_GET['extraFilterVal'] != '') {
		$list->set_filter($_GET['extraFilterIdx'], $_GET['extraFilterVal']);
		$list->exfidx = $_GET['extraFilterIdx'];
		$list->exfval = $_GET['extraFilterVal'];
	} 
	
	$list->get_list_xls(); 
} 
?>