<?php
/**
* AdminProduct Class
* 
* @package		CAMI			
* @since        05/12/2018
* @author		Fernando Rodriguez
 * 
*/ 
class AdminProduct extends Product{
	
	/* General information */
	public $id_product;
	public $id_category;
	public $id_type;
	public $id_brand;
	public $id_status;
	public $SKU;
	public $model;
	public $UPC;
	public $label;
	public $timestamp;
	
	public $supplier_cost;
	public $suggested_cost;
	public $public_price;
	
	/* Fiscal information */
	public $IVA;
	public $IEPS;
	public $SAT_code;
	public $SAT_unit;
	public $account_erp;
	
	/* Operation information */
	public $height;
	public $width;
	public $weight;
	public $depth;
	public $volume;
	public $volumetric_weight;
	
	/* Product related */
	public $products_related = array();
	
		
	/*
	 * save
	 *  Inserts a product record in the Data Base
	* */	
	public function save(){
		global $obj_db;			
		$values = array(
			':id_category' => $this->contact->id_contact,
			':id_type' => $this->uid,
			':id_brand' => $this->uuid_sat,
			':id_status' => $this->folio,
			':SKU' => $this->xml,
			':model' => $this->pdf,
			':UPC' => $this->user,
			':label' => $this->serie->id_serie,
			':timestamp' => $this->id_program,
			':supplier_cost' => $this->comments,
			':suggested_cost' => $this->id_currency,
			':public_price' => $this->id_payment_way,
			':IVA' => $this->id_payment_method,
			':IEPS' => $this->payment_conditions,
			':SAT_code' => $this->cfdi_use,
			':SAT_unit' => $this->reference,
			':account_ERP' => $this->total,
			':height' => $this->preorder,
			':width' => $this->preorder_comments,
			':weight' => $this->type,
			':depth' => $this->status,
			':volume' => $this->status,
			':volumetric_weight' => $this->status,
			':products_related' => $this->status
		);
		if ( $this->id_product > 0 ){
			$values[':id_product'] = $this->id_bill;
			$query = " UPDATE " . PFX_MAIN_DB . "product SET "  
						. " prd_ctr_id_category = :id_category, "  
						. " prd_ptp_id_type	= :id_type, "  
						. " prd_brd_id_brand = :id_brand, "  
						. " prd_pst_id_status = :id_status, "
						. " prd_sku = :sku, "
						. " prd_model = :model, "
						. " prd_upc = :upc, "
						. " prd_label = :label, "
						. " prd_timestamp = :timestamp "
					. " WHERE id_product = :id_product ";

		} else {
			$values[':timestamp'] 	= time();
			$query = "INSERT INTO " . PFX_MAIN_DB . "product "
					." ( prd_ctr_id_category, prd_ptp_id_type, "
					."prd_brd_id_brand, prd_pst_id_status, "
					."prd_sku, prd_model, prd_upc, prd_label, prd_timestamp "
					.") VALUES  ( "
					.":id_category, :id_type, :id_brand, "
					.":id_status, :sku, :model, "
					.":upc, :label, :timestamp ) ";
		}

		$result = $obj_db->execute( $query, $values );
		
		/*echo '<pre>';
		print_r($obj_bd);
		echo '</pre>';
		die('yes');*/

		if ( $result !== FALSE ){
			if ( $this->id_product == 0){
				$this->id_product = $obj_db->get_last_id();
			}
			$this->set_msg('SAVE', " Product general " . $this->id_product . " saved. ");
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to save Product record. ", ERR_DB_EXEC, 3 );
			return FALSE;
		} 
	}
	
	/*
	 * saveSupplierCost
	 *  Inserts a supplier cost record in the Data Base
	* */
	public function saveSupplierCost( $info = array() ){
		if( empty($info) ){
			return false;
		}
		global $obj_db;			
		$values = array(
			':id_product' 	=> $this->id_product,
			':id_supplier' 	=> $info['id_supplier'],
			':quantity' 	=> $info['quantity'],
			':since_date' 	=> $info['since'],
			':until_date' 	=> $info['until'],
			':cost' 		=> $info['cost']
		);
		if ( $this->id_product > 0 && $info['cost'] == $this->supplier_cost ){
			$query = " UPDATE " . PFX_MAIN_DB . "product_cost SET "  
						. " pco_sup_id_supplier = :id_supplier, "  
						. " pco_quantity	= :quantity, " 
						. " pco_since_date = :since_date, "  
						. " pco_until_date = :until_date, "
						. " pco_cost = :cost "
					. " WHERE pco_prd_id_product = :id_product ";

		} else {
			$query = "INSERT INTO " . PFX_MAIN_DB . "product_cost "
					." ( pco_prd_id_product, pco_sup_id_supplier, "
					."pco_quantity, pco_since_date, pco_until_date, "
					."pco_cost "
					.") VALUES  ( "
					.":id_product, :id_supplier, :quantity, "
					.":since_date, :until_date, :cost ) ";
		}

		$result = $obj_db->execute( $query, $values );
		
		/*echo '<pre>';
		print_r($obj_bd);
		echo '</pre>';
		die('yes');*/

		if ( $result !== FALSE ){
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to save Product cost record. ", ERR_DB_EXEC, 3 );
			return FALSE;
		} 
	}
	
	/*
	 * savePrice&Suggested
	 *  Inserts a product price & suggested cost record in the Data Base
	* */	
	public function savePriceAndSuggested(){
		global $obj_db;			
		$values = array(
			':id_product' 		=> $this->id_product,
			':suggested_cost' 	=> $this->id_currency,
			':public_price' 	=> $this->id_payment_way,
			':timestamp' 		=> time() 
		);
		if ( $this->id_product > 0 ){
			$query = " UPDATE " . PFX_MAIN_DB . "product_price SET "  
						. " ppr_suggested_cost = :suggested_cost, "  
						. " ppr_public_price	= :public_price, " 
						. " ppr_timestamp = :timestamp "
					. " WHERE ppr_prd_id_product = :id_product ";

		} else {
			$values[':status'] = 1;
			$query = "INSERT INTO " . PFX_MAIN_DB . "product_cost "
					." ( ppr_prd_id_product, ppr_suggested_cost, "
					."ppr_public_price, ppr_status, ppr_timestamp "
					.") VALUES  ( "
					.":id_product, :suggested_cost, :public_price, "
					.":status, :timestamp ) ";
		}

		$result = $obj_db->execute( $query, $values );
		
		/*echo '<pre>';
		print_r($obj_bd);
		echo '</pre>';
		die('yes');*/

		if ( $result !== FALSE ){
			$this->set_msg('SAVE', " Product price " . $this->id_product . " saved. ");
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to save Product price & suggested record. ", ERR_DB_EXEC, 3 );
			return FALSE;
		}
	}
	
	/*
	 * saveFiscal
	 *  Inserts a product fiscal information record in the Data Base
	* */	
	public function saveFiscal(){
		global $obj_db;			
		$values = array(
			':id_product' 		=> $this->id_product,
			':IVA' 				=> $this->IVA,
			':IEPS' 			=> $this->IEPS,
			':SAT_code' 		=> $this->SAT_code, 
			':SAT_unit' 		=> $this->SAT_unit, 
			':account_titano' 	=> $this->account_erp
		);
		if ( $this->id_product > 0 ){
			$query = " UPDATE " . PFX_MAIN_DB . "product_fiscal_info SET "  
						. " pfi_IVA = :IVA, "  
						. " pfi_IEPS = :IEPS, " 
						. " pfi_SAT_code = :SAT_code "
						. " pfi_SAT_unit = :SAT_unit "
						. " pfi_account_titano = :account_titano "
					. " WHERE pfi_prd_id_product = :id_product ";

		} else {
			$query = "INSERT INTO " . PFX_MAIN_DB . "product_fiscal_info "
					." ( pfi_prd_id_product, pfi_IVA, pfi_IEPS, "
					."pfi_SAT_code, pfi_SAT_unit, pfi_account_titano "
					.") VALUES  ( "
					.":id_product, :IVA, :IEPS, "
					.":SAT_code, :SAT_unit, :account_titano ) ";
		}

		$result = $obj_db->execute( $query, $values );
		
		/*echo '<pre>';
		print_r($obj_bd);
		echo '</pre>';
		die('yes');*/

		if ( $result !== FALSE ){
			$this->set_msg('SAVE', " Product fiscal info " . $this->id_product . " saved. ");
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to save Product fiscal record. ", ERR_DB_EXEC, 3 );
			return FALSE;
		}
	}

/*
	 * saveLogistic
	 *  Inserts a product fiscal information record in the Data Base
	* */	
	public function saveLogistic(){
		global $obj_db;			
		$values = array(
			':id_product' 		=> $this->id_product,
			':height' 			=> $this->height,
			':width' 			=> $this->width,
			':weight' 			=> $this->weight, 
			':depth' 			=> $this->depth, 
			':volume'			=> $this->volume, 
			':volume_weight' 	=> $this->volumetric_weight 
		);
		if ( $this->id_product > 0 ){
			$query = " UPDATE  " . PFX_MAIN_DB . "product_operation_info SET "  
						. " poi_height = :height, "  
						. " poi_width	= :width, " 
						. " poi_weight = :weight, "
						. " poi_depth = :depth, "
						. " poi_volume = :volume, "
						. " poi_volume_weight = :volume_weight "
					. " WHERE poi_prd_id_product = :id_product ";

		} else {
			$query = "INSERT INTO " . PFX_MAIN_DB . "product_operation_info "
					." ( poi_prd_id_product, poi_height, poi_width, "
					."poi_weight, poi_depth, poi_volume, poi_volume_weight "
					.") VALUES  ( "
					.":id_product, :height, :width, "
					.":weight, :depth, :volume, :volume_weight ) ";
		}

		$result = $obj_db->execute( $query, $values );
		
		/*echo '<pre>';
		print_r($obj_bd);
		echo '</pre>';
		die('yes');*/

		if ( $result !== FALSE ){
			$this->set_msg('SAVE', " Product logistic " . $this->id_product . " saved. ");
			return TRUE;
		} else {
			$this->set_error( "An error ocurred while trying to save Product logistic record. ", ERR_DB_EXEC, 3 );
			return FALSE;
		}
	}
}