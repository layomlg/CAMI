<?php
if (!class_exists('Meta')){
	require_once 'class.meta.php';
}
/**
 * class ProductMeta
 */
class ProductMeta extends Meta {
	
	public $options = array();
	public $error 	= array();
	
	function __construct( $id_product = 0, $id_category = 0 ) {
		global $obj_bd;
		$this->class = 'ProductMeta'; 
		
		$query = "SELECT "
					. " id_product_option, id_data_type, dtt_name as dtt_data_type, pro_label, pro_options, pro_timestamp, "
					. " IFNULL(prm_prd_id_product,0) as id_product, prm_value, id_category, ctr_name as ctr_category "
				. " FROM " . PFX_MAIN_DB . "product_option "
					. " INNER JOIN " . PFX_MAIN_DB . "data_type ON pro_dtt_id_data_type = id_data_type "
					. " INNER JOIN " . PFX_MAIN_DB . "category ON pro_ctr_id_category = id_category "
					. "  LEFT JOIN " . PFX_MAIN_DB . "product_meta 	ON prm_pro_id_product_option = id_product_option AND prm_prd_id_product = :id_product  "  
				. " WHERE pro_status = 1 "
					. ( $id_category > 0 ? " AND id_category = $id_category " : "" )  
				. " ORDER BY id_category, id_product_option ASC; "; 
		$opts = $obj_bd->query( $query, array( ':id_product' => $id_product ) ); 
		if ( $opts !== FALSE ){
			if (count($opts) > 0 ){
				foreach ($opts as $k => $opt) {
					$id_cat = $opt['id_category'];  
					if ( !is_array( $this->options[ $id_cat ]  )  )
						$this->options[ $id_cat ] = array(); 
					$this->options[ $id_cat ][] = $this->format_option( $opt ); 
				}
			}
		} else {
			$this->set_error( "Could not retrieve options ", ERR_DB_QRY, 2);
			return NULL;
		}
	}
	
	
	/**
	 * format_option 
	 * formats a MetaOption record from the database to a stdClass format
	 * 
	 * @access	private 
	 * @param 	Array $option record from the DB. 
	 * @return 	stdClass $option of formatted information 
	 */
	private function format_option( $option ){
		$resp = new stdClass;
		$resp->id_option 	= $option['id_product_option'];
		$resp->id_category	= $option['id_category'];
		$resp->category 	= $option['ctr_category']; 
		$resp->id_data_type	= $option['id_data_type'];
		$resp->data_type 	= $option['dtt_data_type'];
		$resp->label	 	= stripslashes($option['pro_label']);
		$resp->options	 	= stripslashes($option['pro_options']);
		$resp->id_product 	= $option['id_product']; 
		$resp->value	 	= stripslashes($option['prm_value']);
		$resp->timestamp 	= $option['pro_timestamp'];
		return $resp;
	} 
	
	
	/**
	 * delete_option 
	 * Changes status flag for the meta option to 0 for the required id_option 
	 * 
	 * @access	public
	 * @param	Integer $id_option for the option to 'delete' 
	 * @return 	Boolean TRUE on success; FALSE otherwise
	 */
	public function delete_option( $id_option = 0){ 
		if ( $id_option > 0 ){
			global $obj_bd;
			$query = "UPDATE " . PFX_MAIN_DB . "product_option SET " 
						. " pro_status = 0, pro_timestamp = :pro_timestamp "
					. " WHERE id_product_option = :id_product_option  ";
			$result = $obj_bd->execute( $query, array( ':pro_timestamp' => time(), ':id_product_option' => $id_option ) );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		}
	}
	
	/**
	 * save_option
	 * Saves the provided Product Option to the DB
	 * 
	 * @param 	stdClass $option information for the product meta option
	 * @return 	Boolean TRUE on success; FALSE otherwise 
	 */ 
	public function save_option( $option ){ 
		if ( $this->validate_option($option) ){ 
			global $obj_bd;
			
			$values = array( 
							':pro_id_category'	=> $option->id_category,
							':pro_id_data_type'	=> $option->id_data_type,
							':pro_label'		=> $option->label,
							':pro_options'		=> $option->options,
							':pro_required'		=> $option->required,
							':pro_blocked'		=> $option->blocked,
							':pro_timestamp'	=> time() 
						);
			
			if ( $option->id_option > 0 ){
				$query = "UPDATE " . PFX_MAIN_DB . "product_option SET "
							. " pro_dtt_id_data_type= :pro_id_data_type, "
							. " pro_prc_id_product_category = :pro_id_category, "
							. " pro_label 			= :pro_label, "
							. " pro_options 		= :pro_options, "
							. " pro_required 		= :pro_required, " 
							. " pro_blocked 		= :pro_blocked, "
							. " pro_status 			= 1, "
							. " pro_timestamp 		= :pro_timestamp "
						. " WHERE id_product_option = :id_product_option "; 
				$values[':id_product_option'] = $option->id_option;
			} else {
				$query = "INSERT INTO " . PFX_MAIN_DB . "product_option "
							. " ( pro_prc_id_product_category, pro_dtt_id_data_type, pro_label, pro_options, pro_required, pro_blocked, pro_status, pro_timestamp ) "
							. " VALUES ( :pro_id_category, :pro_id_data_type, :pro_label, :pro_options, :pro_required, :pro_blocked, 1, :pro_timestamp  ) ";
			} 
			$result = $obj_bd->execute( $query, $values );
			if ( $result !== FALSE ){ 
				return TRUE;
			} else {
				$this->set_error( "An error ocurred while trying to save the record. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		}
	}


	/***
	 * save_values
	 * Saves Meta Values to the options for the provided product 
	 * 
	 * @param	Integer $id_product the ID of the product to save values for the meta options 
	 * @return 	Boolean TRUE on success; FALSE otherwise
	 */
	public function save_values( $id_product ){
		if ( $id_product > 0 ) {
			global $obj_bd;
			$resp = TRUE;
			foreach ($this->options as $k => $option) {
				$query = "SELECT id_product_meta, prm_value FROM " . PFX_MAIN_DB . "product_meta "
							. " WHERE prm_pro_id_product_option = :id_option " 
							. " AND prm_prd_id_product = :id_product ";
				$values = array( ':id_option' => $option->id_option, ':id_product' => $id_product ); 
				$exist = $obj_bd->query( $query, $values );
				if ( $exist !== FALSE ){
					$skip = FALSE;
					if ( is_array( $option->value ) ){
						$value = "";
						foreach ($option->value as $k => $val) {
							$value  .= ($k>0 ? ";" : "") . $val;
						}
					} else {
						$value = $option->value;
					}
					
					if ( count( $exist ) > 0 ){ 
						if ( $option->value != $exist[0]['prm_value'] ){
							$query = " UPDATE " . PFX_MAIN_DB . "product_meta SET prm_value = :prm_value "
									. " WHERE prm_pro_id_product_option = :id_option  AND prm_prd_id_product = :id_product  ";
						} else {
							$skip = TRUE;
						}
					} else {
						$query = " INSERT INTO " . PFX_MAIN_DB . "product_meta (prm_pro_id_product_option, prm_prd_id_product, prm_value ) "
								. " VALUES ( :id_option, :id_product, :prm_value ) "; 
					}
					if ( !$skip ){ 
						$params = array( ':prm_value' => $value, ':id_option' => $option->id_option, ':id_product' => $id_product ); 
						$result = $obj_bd->execute( $query, $params );
						$resp 	= ($resp && $result);
					}
				}
			} 
			return $resp;
		}
	}

	/**
	 * validate_option 
	 * Validates the product option before saving
	 * 
	 * @param	stdClass $option information to validate
	 * @return 	Boolean TRUE if valid; FALSE otherwise 
	 */
	public function validate_option( $option ){
		global $Validate;
		if ( !( $Validate->is_number( $option->id_option ) && $option->id_option >= 0 ) ){
			$this->set_error('Invalid option ID.', ERR_VAL_NOT_INT );
			return FALSE;
		}
		if ( !( $Validate->is_number( $option->id_data_type ) && $option->id_data_type > 0 ) ){
			$this->set_error('Invalid data type ID.', ERR_VAL_NOT_INT );
			return FALSE;
		}
		if ( !( $Validate->is_number( $option->id_category ) && $option->id_category > 0 ) ){
			$this->set_error('Invalid category ID.', ERR_VAL_NOT_INT );
			return FALSE;
		}
		if ( !( $option->label != '' ) ){
			$this->set_error('Invalid Label.', ERR_VAL_EMPTY );
			return FALSE;
		}
		return TRUE; 
	}
}
?>