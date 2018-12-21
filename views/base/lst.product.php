<?php global $catalogue;
if ( !class_exists( 'ProductList' )){
	require_once DIRECTORY_CLASS . "class.product.lst.php";
} 
$list = new ProductList( 'lst_product' );
?>  
			<div class="block">
				<p class="title"> Productos </p>
				<p class="subtitle"> Filtro por: </p>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="productStatus">Rango</label>
							<select class="form-control select-main" id="productStatus">
								<option>Rango</option>
								<option>r1</option>
								<option>r2</option>
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="productStatus">Categoría</label>
							<select class="form-control select-main" id="productStatus">
								<option>Categoría</option>
								<option>c1</option>
								<option>c2</option>
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label for="productStatus">Marcas</label>
							<select class="form-control select-main" id="productStatus">
								<?php echo $catalogue->get_catalogue_options( 'brand' ); ?>
							</select>
						</div>
					</div>
				</div>
				<div class="toggler"> <span class="list"></span> </div>
				
				<div class="art-list-wrap">
					<div class="art-list list"> 
						 <?php $list->get_div_list_html(); ?> 
					</div>
				</div>
				
				<div class='row'> &nbsp; </div>
				
				<div>
					<p> Modificar seleccionados: </p>
					<select name="" id="" class="select-main">
						<option value="">Acciones seleccionadas</option>
						<option value="">Acción 1</option>
						<option value="">Acción 2</option>
					</select>
				</div>
				<div>
					<p> Agregar producto: </p>
					<button class="btn-main"> Individual </button>
					<button class="btn-main"> Excel </button>
				</div>
			</div> 