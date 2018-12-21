<?php
class Catalogue extends Objecto{ 

	public function __construct(){
		$this->class = 'Catalogue'; 
	} 
	
	/**
	 * function get_catalogue()
	 * Returns an array of catalogue records 
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$opt	 	Boolean	if TRUE returns an array for lists od ID's and options.  
	 * @param 		$extra	 	if different to  FALSE uses the argument in the query   
	 * 
	 * @return		$html		Array An array of information from a catalogue , FALSE on error
	 */ 
	public function get_catalogue( $which = '', $opt = FALSE, $extra = FALSE ){
		if ($which != ''){
			switch ($which){  
				case 'brand':
					$query = "SELECT * " . ( $opt ? ", id_brand as id, brd_name as opt " : "") . " FROM " . PFX_MAIN_DB . "brand WHERE brd_status > 0 "
							. ( $extra && is_numeric( $extra ) ? " AND brd_brt_id_brand_type = $extra " : "" )  
							. " ORDER BY brd_name ASC ";  
					break;  
				case 'brand_type':
					$query = "SELECT * " . ( $opt ? ", id_brand_type as id, brt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "brand_type WHERE brt_status > 0 ORDER BY brt_name ASC ";
					break;  
				case 'channel':
					$query = "SELECT * " . ( $opt ? ", id_channel as id, chl_name as opt " : "") . " FROM " . PFX_MAIN_DB . "channel WHERE chl_status > 0 ORDER BY chl_name ASC ";
					break;  
				case 'client':
					$query = "SELECT * " . ( $opt ? ", id_client as id, clt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "client WHERE clt_status > 0 "
							. ( $extra && is_numeric( $extra ) ? " AND clt_chl_id_channel = $extra " : "" )  
							. " ORDER BY clt_name ASC ";  
					break; 
				case 'client_request_type':
					$query = "SELECT * " . ( $opt ? ", id_request_type as id, crt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "client_request_type WHERE crt_status > 0  ORDER BY crt_name ASC ";
					break; 
				case 'cost_type':
					$query = "SELECT * " . ( $opt ? ", id_cost_type as id, pct_name as opt " : "") . " FROM " . PFX_MAIN_DB . "cost_type WHERE pct_status > 0 ORDER BY pct_name ASC ";
					break;  
				case 'data_type':
					$query = "SELECT * " . ( $opt ? ", id_data_type as id, dt_data_type as opt " : "") . " FROM " . PFX_MAIN_DB . "data_type ORDER BY id_data_type "; 
					break;
				case 'delivery_type':
					$query = "SELECT * " . ( $opt ? ", id_delivery_type as id, dlt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "delivery_type ORDER BY dlt_name "; 
					break;
				case 'file_type':
					$query = "SELECT * " . ( $opt ? ", id_file_type as id, ft_file_type as opt " : "") . " FROM " . PFX_MAIN_DB . "file_type ORDER BY id_file_type ";
					break;  
				case 'logistic_provider':
					$query = "SELECT * " . ( $opt ? ", id_provider as id, lgt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "logistic_provider WHERE lgt_status > 0 ORDER BY lgt_name ASC ";
					break;  
				case 'product_type':
					$query = "SELECT * " . ( $opt ? ", id_product_type as id, prt_name as opt " : "") . " FROM " . PFX_MAIN_DB . "product_type WHERE prt_status > 0 ORDER BY prt_name ASC ";
					break;
				case 'profile':
					$query = "SELECT * " . ( $opt ? ", id_profile as id, prf_name as opt " : "") . " FROM " . PFX_MAIN_DB . "profile WHERE prf_status > 0 ORDER BY id_profile ASC ";  
					break;
				case 'program':
					$query = "SELECT * " . ( $opt ? ", id_season as id, pr_program as opt " : "") . " FROM " . PFX_MAIN_DB . "program WHERE pr_status > 0 ORDER BY id_program ASC ";  
					break;
				case 'season':
					$query = "SELECT * " . ( $opt ? ", id_season as id, ssn_name as opt " : "") . " FROM " . PFX_MAIN_DB . "season WHERE ssn_status > 0 ORDER BY ssn_name ASC ";  
					break;
				case 'supplier':
					$query = "SELECT * " . ( $opt ? ", id_supplier as id, sup_name as opt " : "") . " FROM " . PFX_MAIN_DB . "supplier WHERE sup_status > 0 ORDER BY sup_name ASC ";  
					break;
				case 'supplier_address_type':
					$query = "SELECT * " . ( $opt ? ", id_supplier_address_type as id, sat_name as opt " : "") . " FROM " . PFX_MAIN_DB . "supplier_address_type WHERE sat_status > 0 ORDER BY sat_name ASC ";  
					break;	
				case 'supplier_file_type':
					$query = "SELECT * " . ( $opt ? ", id_supplier_file_type as id, sft_name as opt " : "") . " FROM " . PFX_MAIN_DB . "supplier_file_type WHERE sft_status > 0 ORDER BY sft_name ASC ";  
					break;	
				case 'supplier_type':
					$query = "SELECT * " . ( $opt ? ", id_supplier_type as id, sut_name as opt " : "") . " FROM " . PFX_MAIN_DB . "supplier_type WHERE sut_status > 0 ORDER BY sut_name ASC ";  
					break;	
				case 'user':
					$query = "SELECT * " . ( $opt ? ", id_user as id, us_user as opt " : "") . " FROM " . PFX_MAIN_DB . "user WHERE us_status = 1 AND us_pf_id_profile > 3 ORDER BY id_user ";
					break; 
				default:
					$this->error[] = "Invalid catalogue.";
					return FALSE;
			} 
			global $obj_db; 
			$result  = $obj_db->query( $query ); 
			if ( $result !== FALSE ){
				return $result;
			} 
			else return FALSE;
		}
	}
	
	/**
	 * function get_catalogue_options()
	 * Returns an html string of catalogue options from the database to be inserted in a 'selected' control
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$active 	Int		ID of the selected option 
	 * @param 		$option_0	String 	for the first option if string is empty no first option  will be added 
	 * 
	 * @return		$html		String	HTML list of the catalogue options, FALSE on error
	 * 
	 */ 
	public function get_catalogue_options( $which, $selected = 0, $option_0 = 'Elija una opción', $extra = FALSE, $opc_new = FALSE ){
		if ($which != ''){
			$html = "";
			if ( $option_0 != '' )
				$html .= "<option value='0' " . ( $selected == 0 ? "selected='selected'" : "" ) . " >" . $option_0 . "</option>";
			$options = $this->get_catalogue( $which, true, $extra); 
			if ( $options ){
				foreach ($options as $k => $ops) {
					$html .= "<option value='" . $ops['id'] . "' "
					 			. ( $selected == $ops['id'] ? "selected='selected'" : "" ) 
								. "  >" . $ops['opt'] 
							. "</option>";
				}
			}
			if ( $opc_new )
				$html .= "<option value='-1' style='background-color: #222299; color: #EEEEEE;' > Agregar Nuevo </option>";
			
			return $html;
		} else {
			$this->error[] = "Invalid catalogue.";
			return FALSE; 
		}
	}
	
	/**
	 * function get_catalgue_lists()
	 * Returns an html string of a listed tab menu from a catalogue
	 * 
	 * @param 		$which 		String 	catalogue to query
	 * @param 		$active 	Int		ID for the active tab		
	 * @param		$link_tmpl	String	link string template to concatenate to the id to change view
	 * @param 		$tab_0		String 	for the first tab if string is empty no first tab before the catalogue
	 * @param		$css		Strng 	Class for the link in the tab
	 * 
	 * @return		$html		String	HTML list of the catalogue tabs , FALSE on error
	 * 
	 */ 
	public function get_catalogue_lists( $which, $active = 0, $link_tmpl = '', $tab_0 = '', $css = 'tab-link' ){
		if ($which != ''){
			$html = "";
			if ( $tab_0 != '' )
				$html .=  "<li " . ( $active == 0 ? "class='active'" : "" ) . " >" 
							. "<a id='tab_" . $which . "_0' " 
									. " class='" . $css . "' " 
									. " href='" . ( $link_tmpl != '' ? $link_tmpl . "0" : "#" ) . "'>" 
								. $tab_0 
							. "</a>"  
						. "</li>";
			$options = $this->get_catalogue( $which, true); 
			if ( $options ){
				foreach ($options as $k => $ops) {
					$html .= "<li " . ( $active == $ops['id'] ? "class='active'" : "" ) . " >" 
							. "<a id='tab_" . $which . "_" . $ops['id'] . "' " 
									. " class='" . $css . "' " 
									. " href='" . ( $link_tmpl != '' ? $link_tmpl . $ops['id'] : "#" ) . "'>" 
								. $ops['opt']
							. "</a>"  
						. "</li>"; 
				}
			}
			return $html;
		} else {
			$this->set_error("Invalid catalogue.", ERR_VAL_INVALID);
			return FALSE; 
		}
	}	
}
?>