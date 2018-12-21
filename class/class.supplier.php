<?php 
/**
 * Supplier Class
 * 
 * @package 	CAMI
 * @since		19/12/2018
 * 
 */
class Supplier extends Objecto {
	
	public $id_supplier;
	public $name;
	public $code;
	public $RFC;
	public $id_supplier_type;
	public $supplier_type;
	public $utility;
	public $active;
	
	public $additional_info;
	public $address;
	public $files;
	
	/**
	 * __construct()
	 * 
	 * @param	$id_supplier (optional) If set retrieves the requested supplier information from the DB. 
	 *  
	 */
	function __construct( $id_supplier = 0 ){
		if ( is_numeric( $id_supplier ) && $id_supplier > 0 ){
			global $obj_db;
			$query = " SELECT " 
						. " id_supplier, sup_code, sup_name, sup_RFC, sup_utility, sup_active, id_supplier_type, sut_name "
					. " FROM " . PFX_MAIN_DB . "supplier "
						. " INNER JOIN " . PFX_MAIN_DB . "supplier_type ON id_supplier_type = sup_type "
					. " WHERE id_supplier = :id_supplier "; 
			$resp = $obj_db->query( $query, array( ':id_supplier' => $id_supplier ));
			if ( $resp !== FALSE ){
				
				if ( count( $resp ) > 0 ) {
					
					$info = $resp[0];
					$this->id_supplier 		= $info['id_supplier'];
					$this->code				= $info['sup_code'];
					$this->name				= $info['sup_name'];
					$this->RFC				= $info['sup_RFC'];
					$this->utility			= $info['sup_utility'];
					$this->active			= $info['sup_active'];
					
					$this->id_supplier_type	= $info['id_supplier_type'];
					$this->supplier_type	= $info['sut_name']; 
					
					$this->set_additional_info();
					
					$this->set_addresses();
					
				} else {
					$this->clean();
					$this->set_error( "Supplier ($id_supplier) not found in DataBase." , ERR_DB_404 );
				}
				
			} else {
				$this->clean();
				$this->set_error( "DataBase error while querying for Supplier ($id_supplier)" , ERR_DB_QRY );
			} 
		} else {
			$this->clean();
		}
	}
	
	/**
	 * set_additional_info()
	 * retrieves additional information from DataBase for the supplier 
	 * 
	 */
	public function set_additional_info(){
		$this->additional_info = new stdClass;
		if ( $this->id_supplier > 0 ){
			global $obj_db;
			$query = " SELECT "
						. " sai_credit_days, sai_credit_limit, sai_delivery_time, sai_minimum_purchase, id_delivery_type, dlt_name "
					. " FROM " . PFX_MAIN_DB . "supplier_additional_info  "
						. " INNER JOIN " . PFX_MAIN_DB . "delivery_type ON id_delivery_type = sai_dlt_id_delivery_type "
					. " WHERE sai_sup_id_supplier = :id_supplier ";
			
			$resp = $obj_db->query( $query, array( ':id_supplier' => $this->id_supplier ) );
			if ( $resp !== FALSE ){
				if ( count( $resp ) > 0 ){
					$info = $resp[0]; 
					$this->additional_info->credit_days 	= $info['sai_credit_days'];
					$this->additional_info->credit_limit 	= $info['sai_credit_limit'];
					$this->additional_info->delivery_time 	= $info['sai_delivery_time'];
					$this->additional_info->minimum_purchase= $info['sai_minimum_purchase'];
					$this->additional_info->id_delivery_type= $info['id_delivery_type'];
					$this->additional_info->delivery_type	= $info['dlt_name']; 
				} else {
					$this->set_error( "Supplier ($id_supplier) additional information was not found in the DataBase." , ERR_DB_404 );
				}
			} else {
				$this->set_error( "DataBase error while querying for Supplier Additional Information ($id_supplier)." , ERR_DB_QRY );
			} 
		}
	}
	
	/**
	 * set_addresses()
	 * Retrieves all addresses related to the supplier from the DB.
	 * 
	 */
	public function set_addresses(){
		$this->address = array();
		if ( $this->id_supplier > 0 ){
			global $obj_db;
			$query = " SELECT "
						. " id_supplier_address, id_supplier_address_type, sat_name, sua_zip_code, sua_street_detail, sua_locality, sua_district, sua_country "
					. " FROM " . PFX_MAIN_DB . "supplier_address "
						. " INNER JOIN " . PFX_MAIN_DB . "supplier_address_type ON id_supplier_address_type = sua_sat_id_supplier_address_type "
					. " WHERE sua_sup_id_supplier = :id_supplier ";
			$resp = $obj_db->query( $query, array( ':id_supplier' => $this->id_supplier ) );
			if ( $resp !== FALSE ){
				if ( count( $resp ) > 0 ){ 
					foreach ($resp as $k => $addr ) { 
						$a = new stdClass;
						$a->id_address 		= $addr['id_supplier_address']; 
						$a->id_address_type	= $addr['id_supplier_address_type'];
						$a->address_type	= $addr['sat_name'];
						$a->street			= $addr['sua_street_detail'];
						$a->locality		= $addr['sua_locality'];
						$a->district		= $addr['sua_district'];
						$a->country			= $addr['sua_country'];
						$a->zipcode			= $addr['sua_zip_code'];
						
						$this->address[ $addr['id_supplier_address'] ] = $a; 
					}
				} else {
					$this->set_error( "Supplier ($id_supplier) addresses were not found in the DataBase." , ERR_DB_404 );
				}
			} else {
				$this->set_error( "DataBase error while querying for Supplier Addresses ($id_supplier)." , ERR_DB_QRY );
			} 
		} 
	} 
	
	/**
	 * clean()
	 * resets all class values 
	 * 
	 */
	public function clean(){
		
		$this->id_supplier 		= 0;
		$this->name 			= "";
		$this->code 			= "";
		$this->RFC				= "";
		$this->id_supplier_type = 0;
		$this->supplier_type	= "";
		$this->utility 			= 0;
		$this->active 			= 0;
		
		$this->additional_info 	= new stdClass;
		$this->address 			= array();
		$this->files 			= array();
		
	}
}
?>