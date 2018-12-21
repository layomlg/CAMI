<?php

/**
 * ProductList
 */
class ProductList extends SQL_Server_DataTable {

	function __construct( $which = '', $table_id = '',$fdix = '',$fval = '') {
		$this->class = 'ProductList'; 
		if ($which != '') {
			$this->which = $which;
			parent::__construct($which, $table_id);
			if(!empty($fdix) && !empty($fval)){
				$this->fidx = $fdix;
				$this->fval = $fval;
			}
			$this->set_query();
			$this->set_template(); 
		}
		else {
			$this->clean(); 
			$this->error[] = "Listado inválido.";
		}  
	}

	private function set_query(){
		switch ( $this->which ) { 
			case 'lst_product': 
				if ( $this->sidx != 'id'){
					$this->sidx = $this->sidx;
					$this->sord = $this->sord; 
				} else {
					$this->sidx = 'id_product';
					$this->sord = 'DESC';
				} 
				$this->query_count = " SELECT id_product FROM " . PFX_MAIN_DB . "product "    
										. " INNER JOIN " . PFX_MAIN_DB . "brand ON id_brand = prd_brd_id_brand "
	                      				. "  LEFT JOIN " . PFX_MAIN_DB . "product_price  ON ppr_prd_id_product = id_product AND ppr_status > 0 " 
									. " WHERE prd_pst_id_status > 0 "; 
				$this->query = " SELECT "
								. " ROW_NUMBER() OVER (ORDER BY $this->sidx $this->sord) as row, " 
								. " id_product, prd_label, prd_sku, prd_model, brd_name, ppr_public_price " 
	                      . " FROM " . PFX_MAIN_DB . "product "  
	                      	. " INNER JOIN " . PFX_MAIN_DB . "brand ON id_brand = prd_brd_id_brand "
	                      	. "  LEFT JOIN " . PFX_MAIN_DB . "product_price  ON ppr_prd_id_product = id_product AND ppr_status > 0 " 
	                      . " WHERE prd_pst_id_status > 0 "; 
				if(!empty($this->fidx) && !empty($this->fval)){
					$this->set_filter($this->fidx, $this->fval);
          		}
				break; 
				 
			case 'lst_supplier': 
				if ( $this->sidx != 'id'){
					$this->sidx = $this->sidx;
					$this->sord = $this->sord; 
				} else {
					$this->sidx = 'id_supplier';
					$this->sord = 'DESC';
				} 
				$this->query_count = " SELECT id_supplier FROM " . PFX_MAIN_DB . "supplier WHERE sup_status > 0 "; 
				$this->query = " SELECT "
								. " ROW_NUMBER() OVER (ORDER BY $this->sidx $this->sord) as row, " 
								. " id_supplier, sup_code, sup_name, sup_RFC, sup_active " 
	                      . " FROM " . PFX_MAIN_DB . "supplier "  
	                      . " WHERE sup_status > 0 "; 
				if(!empty($this->fidx) && !empty($this->fval)){
					$this->set_filter($this->fidx, $this->fval);
          		}
				break; 
          }
          $this->sort = " ORDER BY " . $this->sidx . " " . $this->sord . " ";
      }

	private function set_template() {
		switch ($this->which) {
			
			case 'lst_product':
				$this->title = "Productos"; 
				$this->columns = array(
						array('idx' => '',			 		'lbl' => '', 		 		'sortable' => FALSE,  	'searchable' => FALSE, 	'cls' => 'check' ),
						array('idx' => 'img',				'lbl' => 'IMG', 	 		'sortable' => FALSE,  	'searchable' => FALSE, 	'cls' => 'img', 'width' => '150px' ),
						array('idx' => 'prd_sku',			'lbl' => 'SKU',				'sortable' => TRUE, 	'searchable' => TRUE, 	'cls' => 'sku'), 
						array('idx' => 'prd_label', 		'lbl' => 'Etiqueta',  		'sortable' => TRUE,  	'searchable' => TRUE, 	'cls' => 'title'), 
						array('idx' => 'prd_model', 		'lbl' => 'Modelo', 	 		'sortable' => TRUE,  	'searchable' => TRUE, 	'cls' => 'model' ),  
						array('idx' => 'brd_name',			'lbl' => 'Marca', 			'sortable' => TRUE,  	'searchable' => TRUE, 	'cls' => 'brand' ),
                        array('idx' => 'ppr_public_price', 	'lbl' => 'Precio Público',	'sortable' => TRUE,  	'searchable' => TRUE, 	'cls' => 'price' ), 
						array('idx' => 'actions', 			'lbl' => 'Acciones', 		'sortable' => FALSE, 	'searchable' => FALSE,  'cls' => 'actions', 'width' => '120px')
				);
				$this->template = DIRECTORY_VIEWS . "/lists/lst.product.div.php";
				break;
				
			case 'lst_supplier':
				$this->title = "Proveedores"; 
				$this->columns = array(
						array('idx' => '',			 		'lbl' => '', 		 		'sortable' => FALSE,  	'searchable' => FALSE, 	'cls' => 'check', 	'width' => '10%' 	), 
						array('idx' => 'sup_code',			'lbl' => 'IdProveedor',		'sortable' => TRUE, 	'searchable' => TRUE, 	'cls' => 'name', 	'width' => '15%'	), 
						array('idx' => 'sup_name',			'lbl' => 'Proveedor',		'sortable' => TRUE, 	'searchable' => TRUE, 	'cls' => 'name', 	'width' => '35%'	), 
						array('idx' => 'sup_RFC', 			'lbl' => 'RFC',  			'sortable' => TRUE,  	'searchable' => TRUE, 	'cls' => 'rfc', 	'width' => '15%'	), 
						array('idx' => 'sup_active', 		'lbl' => 'Activo', 	 		'sortable' => TRUE,  	'searchable' => FALSE, 	'cls' => 'active', 	'width' => '10%' 	), 
						array('idx' => 'actions', 			'lbl' => 'Acciones', 		'sortable' => FALSE, 	'searchable' => FALSE,  'cls' => 'actions', 'width' => '15%'	)
				);
				$this->template = DIRECTORY_VIEWS . "/lists/lst.supplier.div.php";
				break; 
				
		}
	}
}
?>