<?php global $catalogue;
if ( !class_exists( 'ProductList' )){
	require_once DIRECTORY_CLASS . "class.product.lst.php";
} 
$list = new ProductList( 'lst_supplier' );
?>  
<script>
	var frm_supplier = '<?php echo SUPPLIER_FRM ?>'; 
	var current		 = '<?php echo SUPPLIERS ?>';
</script>
	<div class="block">
		<p class="title">
			Proveedores
		</p>  
		<div class="art-list-wrap">
			<div class="art-list list"> 
				 <?php  
				 $list->get_div_list_html(); 
				 ?> 
			</div>
		</div> 
		
		<div class='row'> &nbsp; </div>
		
		<div>
			<p> Agregar proveedor: </p>
			<button class="btn-main" onclick='edit_supplier(0);'> Individual </button>
			<button class="btn-main"> Excel </button>
		</div>
	</div> 