<?php
/**
* Class Settings
* 
* @package		MLG - Base
* @since        20/11/2018 
*/ 
class Settings extends Objecto{
 	
	public $error = array();
	
	function __construct(){
		$this->class = "Settings"; 
	}
	
	/**
	* get_settings_option()
	* returns the value of the $option from the DB
	* 
	* @param         $option String option key
	* @param 		 $instance Integer (Default 0)  
	* @param 		 $user 		Integer (Default 0) If > 0 sets user filter for the option 
	* 
	* @return 		String value of the option from the DB. NULL on error.
	*/  
	public function get_settings_option( $option = '' ){
		if( empty($option)  ){
			$this->set_error( "Invalid option parameter. ", ERR_VAL_EMPTY); 
			return false;
		}
		global $obj_db, $Session; 
		$query = " SELECT stg_value FROM " . PFX_MAIN_DB . "settings "
				." WHERE stg_name = :name " 
				." AND stg_status = 1 "; 
					
		$params = array( ':name' =>   $option );
		
		$record	= $obj_db->query( $query, $params );
		
		if ( $record !== FALSE ){
			if (count($record) > 0 ){
				return json_decode($record[0]['stg_value'], TRUE );
			} else {
				$this->set_error( "Option not found (" . $option . ")", ERR_DB_NOT_FOUND, 1);
				return NULL;
			}
		}else {
			$this->set_error( "Could not retrieve option (" . $option . ")", ERR_DB_QRY, 2);
			return NULL;
		}
	}
	
	/**
	* save_settings_option()
	* Saves an the value for an option in the DB.
	* 
	* @param         $option 	String option key
	* @param 		 $value 	String option value 
	* @param 		 $instance 	Integer (Default 0)  
	* @param 		 $user 		Boolean (Default FALSE) If TRUE sets Session user as filter for the option  
	* 
	* @return 		String value of the option from the DB. NULL on error.
	*/  
	public function save_settings_option( $option = '', $value = '', $instance = 0, $user = FALSE  ){
		if( empty($option) || empty($value) ){
			$this->set_error( "Invalid parameters to save. ", ERR_VAL_EMPTY ); 
			return false;
		}
		global $obj_bd, $Session;
		$query_ex = " SELECT id_settings_option, so_value, so_timestamp  FROM " . PFX_MAIN_DB . "settings_option "
				. " WHERE so_option = :option " 
					. (( $user ) ? " AND so_us_id_user = :so_user " : "")
					. " AND so_status = 1 ";
					
		$params = array( ':option' =>   $option );
		
		if ( $user )  
			$params[':so_user'] = $Session->get_id() ; 
		
		$exists	= $obj_bd->query( $query_ex, $params );  
		
		if ( $exists !== FALSE ){ 
			$values = array( 
					':so_option' 	=> $option, 
					':so_value' 	=> $value, 
					':so_timestamp' => time(), 
					':so_id_user'	=> $Session->get_id(),
				);
				
			if ( count($exists) > 0 ){
				$values[':id_option'] = $exists[0]['id_settings_option'];
				$query = "UPDATE  " . PFX_MAIN_DB . "settings_option SET "
							. " so_option 	 = :so_option , "
							. " so_value  	 = :so_value , "
							. " so_timestamp = :so_timestamp , "
							. " so_us_id_user= :so_id_user , "
							. " so_status 	 = 1 "
						. " WHERE id_settings_option  = :id_option ";
			}else{ 
				$query = " INSERT INTO " . PFX_MAIN_DB . "settings_option " 
							. "(so_option, so_value, so_timestamp, so_us_id_user, so_status )"
							. "VALUES ( :so_option, :so_value, :so_timestamp, :so_id_user, 1 )";
			} 
						
			$result = $obj_bd->execute( $query, $values );
			
			if ( $result !== FALSE ){
				$this->set_msg('SAVE', "Option " . $option . " saved. ");
				return TRUE;
			} else { 
				$this->set_error( "An error ocurred while saving the option " . $option . ". ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		}
		else {
			$this->set_error( "An error ocurred while querying the DB for the option " . $option . ". ", ERR_DB_QRY ); 
			return FALSE;
		}
	}
}
?>