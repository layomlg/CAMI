<?php
/**
* Product Class
* 
* @package		CAMI			
* @since        05/12/2018
* @author		Fernando Rodriguez
 * 
*/ 
class Product extends Objecto{
	
	
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
	 * __construct()
	 * 
	 * @param $id_product (optional) If set, populates values from DB record.
	 * @param $op_info (optional) If set, populates operational info values from DB record.
	 * @param $fc_info (optional) If set, populates fiscal info values from DB record.
	 * @param $pr_info (optional) If set, populates products related values from DB record.
	 * 
	 * */
	
	function __construct( $id_product = 0, $op_info = false, $fc_info = false, $pr_info = false ){
		global $obj_db;
		
		if( !empty($id_product) ){
			
			$product_query = "SELECT * "
				."FROM ".PFX_MAIN_DB."product "
				."LEFT JOIN ".PFX_MAIN_DB."product_cost "
				."ON pco_prd_id_product = id_product "
				."INNER JOIN ".PFX_MAIN_DB."product_price "
				."ON ppc_prd_id_product = id_product "
				."WHERE id_product = :id_product "
				."AND pco_since_date >= :since "
				."AND pco_until_date <= :until "
				."AND pco_status = 1 "
				."AND ppc_status = 1 ";
				
			$product_info = $obj_db->query( $query, array( ':id_product' => $id_product ) );
			
			if( !empty($product_info) ){
				$this->id_product 		= $product_info[0]['id_product'];
				$this->id_category 		= $product_info[0]['prd_ctr_id_category'];
				$this->id_type 			= $product_info[0]['prd_ptp_id_type'];
				$this->id_brand 		= $product_info[0]['prd_brd_id_brand'];
				$this->id_status		= $product_info[0]['prd_pst_id_status'];
				$this->SKU 				= $product_info[0]['prd_sku'];
				$this->model 			= $product_info[0]['prd_model'];
				$this->UPC 				= $product_info[0]['prd_upc'];
				$this->label			= $product_info[0]['prd_timestamp'];
				$this->supplier_cost	= $product_info[0]['pco_cost'];
				$this->suggested_cost	= $product_info[0]['ppc_suggested_cost'];
				$this->public_price		= $product_info[0]['ppc_public price'];
				
				if( !empty($op_info) ){
					$this->getLogisticInfo();
				}
				if( !empty($op_info) ){
					$this->getFiscalInfo();
				}
				if( !empty($op_info) ){
					$this->getProductRelatedInfo();
				}
				
			}else{
				$this->clean();
				$this->set_error( "An error ocurred while querying the Data Base to get product info. ", ERR_DB_QRY, 2 );
			}
		}else{
			$this->clean();
		}
	}

	/*
	 * getOperationInfo() Get the logistic information
	 * 
	 * 
	 * */

	public function getLogisticInfo(){
		if( empty($this->id_product) ){
			return false;
		}
		global $obj_db;
		
		$product_query = "SELECT * "
			."FROM ".PFX_MAIN_DB."product_operation_info "
			."WHERE poi_prd_id_product = :id_product ";
			
		$product_op_info = $obj_db->query( $query, array( ':id_product' => $this->id_product ) );
		
		if( !empty($product_op_info) ){
			$this->height 	= $product_op_info[0]['poi_height'];
			$this->width 	= $product_op_info[0]['poi_width'];
			$this->weight 	= $product_op_info[0]['poi_weight'];
			$this->depth	= $product_op_info[0]['poi_depth'];
			$this->volume 	= $product_op_info[0]['poi_volume'];
			$this->volumetric_weight = $product_op_info[0]['poi_volume_weight'];
			return true;			
		}else{
			$this->set_error( "An error ocurred while querying the Data Base to get product operational info. ", ERR_DB_QRY, 2 );
			return false;
		}
	}
	
	/*
	 * getFiscalInfo() Get the fiscal information
	 * 
	 * 
	 * */

	public function getFiscalInfo(){
		if( empty($this->id_product) ){
			return false;
		}
		global $obj_db;
		
		$product_query = "SELECT * "
			."FROM ".PFX_MAIN_DB."product_fiscal_info "
			."WHERE pfi_prd_id_product = :id_product ";
			
		$product_fc_info = $obj_db->query( $query, array( ':id_product' => $this->id_product ) );
		
		if( !empty($product_op_info) ){
			$this->height 	= $product_op_info[0]['pfi_IVA'];
			$this->width 	= $product_op_info[0]['pfi_IEPS'];
			$this->weight 	= $product_op_info[0]['pfi_SAT_code'];
			$this->depth	= $product_op_info[0]['pfi_SAT_unit'];
			$this->volume 	= $product_op_info[0]['pfi_account_Titano'];
			return true;			
		}else{
			$this->set_error( "An error ocurred while querying the Data Base to get product fiscal info. ", ERR_DB_QRY, 2 );
			return false;
		}
	}
	
	/*
	 * getProductRelatedInfo() Get the products related
	 * 
	 * 
	 * */

	public function getProductRelatedInfo(){
		if( empty($this->id_product) ){
			return false;
		}
		global $obj_db;
		
		$product_query = "SELECT TOP 10 pmc.pco_cost, pmc.pco_prd_id_product, * "
				."FROM ( "
					."SELECT *, ROW_NUMBER() OVER ( partition by pco_prd_id_product ORDER BY pco_until_date DESC ) [valor] "
					."FROM ".PFX_MAIN_DB."product_cost "
					."WHERE pco_prd_id_product IN ( "
						."SELECT prr_prd_id_product_related "
						."FROM ".PFX_MAIN_DB."product_related "
						."WHERE prr_prd_id_product = :id_product "
					." ) "
				." ) pmc "
				."RIGHT JOIN ".PFX_MAIN_DB."product "
				."ON  pmc.pco_prd_id_product = id_product "
				."LEFT JOIN ".PFX_MAIN_DB."product_price "
				."ON ppc_prd_id_product = id_product "
				."WHERE pmc.valor = 1";
			
		$product_pr_info = $obj_db->query( $query, array( ':id_product' => $this->id_product ) );
		
		if( !empty($product_pr_info) ){
			foreach ($product_pr_info as $key => $value) {
				$this->products_related[] = array(
					'id_product' => $value['id_product'],
					'id_category' => $value['prd_ctr_id_category'],
					'id_type' => $value['prd_ptp_id_type'],
					'id_brand' => $value['prd_brd_id_brand'],
					'id_status' => $value['prd_pst_id_status'],
					'SKU' => $value['prd_sku'],
					'model' => $value['prd_model'],
					'label' => $value['prd_upc'],
					'supplier_cost' => $value['pco_cost'],
					'suggested_cost' => $value['ppr_suggested_cost'],
					'public_price' => $value['ppr_pubic_price']
				);
			}
			return true;			
		}else{
			$this->set_error( "An error ocurred while querying the Data Base to get product related info. ", ERR_DB_QRY, 2 );
			return false;
		}
	}
	
	/*
	 * getOperationInfo() Get the logistic information
	 * 
	 * 
	 * */

	public function getSupplierCostDeatil(){
		if( empty($this->id_product) ){
			return false;
		}
		global $obj_db;
		
		$product_query = "SELECT TOP 10 pmc.pco_cost, pmc.pco_prd_id_product, * "
				."FROM ( "
					."SELECT *, ROW_NUMBER() OVER ( partition by pco_prd_id_product ORDER BY pco_until_date DESC ) [valor] "
					."FROM ".PFX_MAIN_DB."product_cost "
					."WHERE pco_prd_id_product IN ( "
						."SELECT prr_prd_id_product_related "
						."FROM ".PFX_MAIN_DB."product_related "
						."WHERE prr_prd_id_product = :id_product "
					." )"
				." ) pmc "
				."RIGHT JOIN ".PFX_MAIN_DB."product "
				."ON  pmc.pco_prd_id_product = id_product "
				."LEFT JOIN ".PFX_MAIN_DB."product_price "
				."ON ppc_prd_id_product = id_product "
				."WHERE pmc.valor = 1";
			
		$product_pr_info = $obj_db->query( $query, array( ':id_product' => $this->id_product ) );
		
		if( !empty($product_pr_info) ){
			foreach ($product_pr_info as $key => $value) {
				$this->products_related[] = array(
					'id_product' => $value['id_product'],
					'id_category' => $value['prd_ctr_id_category'],
					'id_type' => $value['prd_ptp_id_type'],
					'id_brand' => $value['prd_brd_id_brand'],
					'id_status' => $value['prd_pst_id_status'],
					'SKU' => $value['prd_sku'],
					'model' => $value['prd_model'],
					'label' => $value['prd_upc'],
					'supplier_cost' => $value['pco_cost'],
					'suggested_cost' => $value['ppr_suggested_cost'],
					'public_price' => $value['ppr_pubic_price']
				);
			}
			return true;			
		}else{
			$this->set_error( "An error ocurred while querying the Data Base to get product related info. ", ERR_DB_QRY, 2 );
			return false;
		}
	}
	
	/**
	 * clean() Clean all variables
	 */
	public function clean(){
		$this->id_product 	= 0;
		$this->id_category 	= 0;
		$this->id_type 		= 0;
		$this->id_brand 	= 0;
		$this->id_status	= 0;
		$this->SKU 			= '';
		$this->model 		= '';
		$this->UPC 			= '';
		$this->label		= '';
	}
	
	/*
	 * getArray() Get an array of vsriables with indexes
	 */
	public function getArray(){
		return array(
			'id_product' 	=> $this->id_product,
			'id_category' 	=> $this->id_category,
			'id_type' 		=> $this->id_type,
			'id_brand' 		=> $this->id_brand,
			'id_status' 	=> $this->id_status,
			'SKU' 			=> $this->SKU,
			'model' 		=> $this->model,
			'UPC' 			=> $this->UPC,
			'label' 		=> $this->label,
			'timestamp' 	=> $this->timestamp
		);
	}
}
