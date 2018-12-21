<div class="block">
	<div class="row">
		<div class="col-sm-6">
			<h3 class="title"> Producto <span class="f-left">9999</span></h3>
		</div>
		<div class="col-sm-3">
			<button class="btn-main">
				Cerrar
			</button>
		</div>
		<div class="col-sm-3">
			<button class="btn-main">
				Guardar
			</button>
		</div>
	</div>
	<div class="row">
		<form id="frm_product" action="product.php" method="post"
		class="form-data form-data-one-edit" enctype="multipart/form-data" >
			<div class="row">
				<div class="col-sm-4 placeholder-img" >
					<img src="img/1x1.png">
					<div class="uploaded-picture"></div>
					<div class="upload-picture" onclick="$('#prd_image').trigger('click');">
						<div>
							<img src="img/svg/pencil.svg">
							<p>
								Editar fotografía
							</p>
						</div>

					</div>
					<input type="file" id="prd_image" name="prd_image" class="invisible" />
				</div>
				<div class="col-sm-4">
					<div class="input-group">
						<label for="productName">Etiqueta</label>
						<input type="text" class="form-control" id="productName"
						aria-describedby="productNameHelp" placeholder="Ingresa una etiqueta">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productNameHelp" class="invalid-feedback">
							Por favor ingresa una etiqueta correcta
						</div>
					</div>
					<div class="input-group">
						<label for="productDescription">Descripci&oacute;n</label>
						<textarea type="text" class="form-control" id="productDescription" 
							aria-describedby="productDescriptionHelp" 
							placeholder="Ingresa la descripci&oacute;n del producto" rows="9"></textarea>
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productDescriptionHelp" class="invalid-feedback">
							Por favor ingresa una descripci&oacute;n correcta
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="input-group">
						<label for="productSKU">SKU</label>
						<input type="text" class="form-control" id="productSKU"
						aria-describedby="productSKUHelp" placeholder="Ingresa el SKU">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productSKUHelp" class="invalid-feedback">
							Por favor ingresa un SKU valido
						</div>
					</div>
					<div class="input-group">
						<label for="productModel">Modelo</label>
						<input type="text" class="form-control" id="productModel"
						aria-describedby="productModelHelp" placeholder="Ingresa el Modelo">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productModelHelp" class="invalid-feedback">
							Por favor ingresa un Modelo valido
						</div>
					</div>
					<div class="input-group">
						<label for="productUPC">Codigo de barras</label>
						<input type="text" class="form-control" id="productUPC"
						aria-describedby="productUPCHelp" placeholder="Ingresa el codigo de barras">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productUPCHelp" class="invalid-feedback">
							Por favor ingresa un codigo de barras valido
						</div>
					</div>
					<div class="input-group">
						<label for="productStatus">Estatus</label>
						<select class="form-control select-main" id="productStatus">
							<option>Activo</option>
							<option>Inactivo</option>
						</select>
						<div class="input-group-append">
							<button class="btn btn-outline-secondary one-edit" type="button">
								Editar
							</button>
						</div>
						<div id="productStatusHelp" class="invalid-feedback">
							Por ingresa el estatus
						</div>
					</div>
				</div>
				<hr>
				<div class="col-sm-12">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" href="#general" role="tab" data-toggle="tab">General</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#logistic" role="tab" data-toggle="tab">Huella Logistica</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#supplier" role="tab" data-toggle="tab">Proveedor</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#additional" role="tab" data-toggle="tab">Adicionales</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#related" role="tab" data-toggle="tab">Relacionados</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active show" id="general">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productName">Precio P&uacute;blico</label>
										<input type="text" class="form-control" id="productPublicPrice"
										aria-describedby="productPublicPriceHelp" placeholder="Ingresa el precio p&uacute;blico">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productPublicPriceHelp" class="invalid-feedback">
											Por favor ingresa el precio p&uacute;blico
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productName">Precio Sugerido</label>
										<input type="text" class="form-control" id="productSuggestedPrice"
										aria-describedby="productSuggestedPriceHelp" placeholder="Ingresa el precio sugerido">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productSuggestedPriceHelp" class="invalid-feedback">
											Por ingresa el precio sugerido
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productType">Tipo</label>
										<select class="form-control select-main" id="productType">
											<option>Uno</option>
											<option>Dos</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productTypeHelp" class="invalid-feedback">
											Por favor ingresa el tipo
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productType">Categoria</label>
										<select class="form-control select-main" id="productCategory">
											<option>Categoria 1</option>
											<option>Categoria 2</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productCategoryHelp" class="invalid-feedback">
											Por favor ingresa una categoria
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productType">Marca</label>
										<select class="form-control select-main" id="productBrand">
											<option>Marca 1</option>
											<option>Marca 2</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productBrandHelp" class="invalid-feedback">
											Por favor ingresa la marca
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="logistic">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productHeight">Alto</label>
										<input type="text" class="form-control" id="productHeight"
										aria-describedby="productHeightHelp" placeholder="Ingresa el Alto">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productHeightHelp" class="invalid-feedback">
											Por favor ingresa el alto del producto
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productWidth">Ancho</label>
										<input type="text" class="form-control" id="productWidth"
										aria-describedby="productWidthHelp" placeholder="Ingresa el ancho">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productWidthHelp" class="invalid-feedback">
											Por favor ingresa el ancho del producto
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productWeight">Peso</label>
										<input type="text" class="form-control" id="productWeight"
										aria-describedby="productWeightHelp" placeholder="Ingresa el peso">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productWeightHelp" class="invalid-feedback">
											Por favor el peso del producto
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productDepth">Profundidad</label>
										<input type="text" class="form-control" id="productDepth"
										aria-describedby="productDepthHelp" placeholder="Ingresa la profundidad">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productDepthHelp" class="invalid-feedback">
											Por favor ingresa la profundidad del producto
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productVolume">Volumen</label>
										<input type="text" class="form-control"  id="productVolume" placeholder="Ingresa el volumen" />
										<div class="input-group-append" >
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productVolumeHelp" class="invalid-feedback">
											Por favor ingresa el volumen
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productVolumeWeight">Peso volumetrico</label>
										<input type="text" class="form-control"  id="productVolumeWeight" placeholder="Ingresa el peso volumetrico" />
										<div class="input-group-append" >
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productVolumeWeightHelp" class="invalid-feedback">
											Por favor ingresa el peso volumetrico
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="supplier">
							<div class="row">
								<table class="table table-hover">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Proveedor</th>
											<th scope="col">Minimo de compra</th>
											<th scope="col">Costo</th>
											<th scope="col">Vigencia</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">1</th>
											<td>Proveedor 1</td>
											<td>20</td>
											<td>10298.22</td>
											<td>del 15/12/18 al 31/12/18</td>
										</tr>
										<tr>
											<th scope="row">2</th>
											<td>Proveedor 2</td>
											<td>50</td>
											<td>8999.50</td>
											<td>del 15/12/18 al 31/12/18</td>
										</tr>
										<tr>
											<th scope="row">3</th>
											<td>Proveedor 3</td>
											<td>25</td>
											<td>10050.89</td>
											<td>del 25/12/18 al 31/12/18</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<button class="btn-main">
										+ Agregar
									</button>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="additional">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productMeta1">Puertos HDMI</label>
										<input type="text" class="form-control" id="productMeta1"
										aria-describedby="productMeta1Help" placeholder="#">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productMeta1Help" class="invalid-feedback">
											Por favor ingresa un valor correcto
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productMeta2">Salida Optica</label>
										<select class="form-control select-main" id="productMeta2">
											<option>Si</option>
											<option>No</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productMeta2Help" class="invalid-feedback">
											Por ingresa un valor correcto
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productMeta3">SMART</label>
										<select class="form-control select-main" id="productMeta3">
											<option>Si</option>
											<option>No</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productMeta3Help" class="invalid-feedback">
											Por favor ingresa un valor correcto
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group">
										<label for="productMeta4">HDR</label>
										<select class="form-control select-main" id="productMeta4">
											<option>Si</option>
											<option>No</option>
										</select>
										<div class="input-group-append">
											<button class="btn btn-outline-secondary one-edit" type="button">
												Editar
											</button>
										</div>
										<div id="productMeta4Help" class="invalid-feedback">
											Por favor ingresa un valor correcto
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="related">
							<div class="row">
								<table class="table table-hover">
									<thead>
										<tr>
											<th scope="col">ID</th>
											<th scope="col">Imagen</th>
											<th scope="col">Etiqueta</th>
											<th scope="col">SKU</th>
											<th scope="col">Modelo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">1</th>
											<td><img src="" /></td>
											<td>Producto 1</td>
											<td>SDSF232322</td>
											<td>112457</td>
										</tr>
										<tr>
											<th scope="row">2</th>
											<td><img src="" /></td>
											<td>Producto 2</td>
											<td>MLG12312331</td>
											<td>1000</td>
										</tr>
										<tr>
											<th scope="row">3</th>
											<td><img src="" /></td>
											<td>Producto 3</td>
											<td>MLG478</td>
											<td>12</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<button type="button" class="btn-main" onclick="$('#prdcts_related').toggleClass('invisible');">
										Agregar relacionado
									</button>
								</div>
							</div>
							<div class="row invisible col-sm-12" id="prdcts_related">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Buscar" 
										aria-label="Buscar" 
										aria-describedby="button-addon">
								  	<div class="input-group-append" id="button-addon">
								    	<button class="btn btn-outline-secondary" 
								    		type="button">Buscar</button>
								    	<button class="btn btn-outline-secondary" 
								    		type="button" onclick="$('#prdcts_related_filters').toggleClass('invisible');">
								    		<i class="fa fa-arrow-down"></i>
								    	</button>
								  	</div>
								 </div>
								 <div id="prdcts_related_filters" class="row invisible">
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
												<?php echo $catalogue->get_catalgue_options( 'brand' ); ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row toggler col-sm-12">
									<span class="list"></span>
								</div>
								<div class="art-list-wrap">
									<div class="art-list list">
										<div class="art-item">
											<div class="art-check">
												<p></p>
											</div>
											<div class="art-img">
												<p>
													IMG
												</p>
											</div>
											<div class="art-title">
												<p>
													Título
												</p>
											</div>
											<div class="art-price">
												<p>
													Precio
												</p>
											</div>
											<div class="art-status">
												<p>
													Estado
												</p>
											</div>
											<div class="art-actions">
												<p>
													Acciones
												</p>
											</div>
										</div>
										<div class="art-item">
											<div class="art-check">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input">
													<span class="custom-control-indicator"></span> </label>
											</div>
											<div class="art-img">
												<img src="img/1x1.png" alt="SKU">
											</div>
											<div class="art-title">
												<p>
													Carrito eléctrico X-Speed, negro. Mytoy®
												</p>
											</div>
											<div class="art-price">
												<p>
													$123.456.00
												</p>
											</div>
											<div class="art-status">
												<p>
													Activo
												</p>
											</div>
											<div class="art-actions">
												<div class="form-group">
													<select class="form-control select-main" id="productStatus">
														<option>Acciones</option>
														<option>Ver</option>
														<option>Editar</option>
														<option>Eliminar</option>
													</select>
												</div>
											</div>
										</div>
										<div class="art-item">
											<div class="art-check">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input">
													<span class="custom-control-indicator"></span> </label>
											</div>
											<div class="art-img">
												<img src="img/1x1.png" alt="SKU">
											</div>
											<div class="art-title">
												<p>
													Procesador de alimentos Prime 1000 W. Nutribullet®
												</p>
											</div>
											<div class="art-price">
												<p>
													$123.456.00
												</p>
											</div>
											<div class="art-status">
												<p>
													Activo
												</p>
											</div>
											<div class="art-actions">
												<div class="form-group">
													<select class="form-control select-main" id="productStatus">
														<option>Acciones</option>
														<option>Ver</option>
														<option>Editar</option>
														<option>Eliminar</option>
													</select>
												</div>
											</div>
										</div>
										<div class="art-item">
											<div class="art-check">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input">
													<span class="custom-control-indicator"></span> </label>
											</div>
											<div class="art-img">
												<img src="img/1x1.png" alt="SKU">
											</div>
											<div class="art-title">
												<p>
													Altavoz inalámbrico SoundTouch 30, negro. Bose®
												</p>
											</div>
											<div class="art-price">
												<p>
													$123.456.00
												</p>
											</div>
											<div class="art-status">
												<p>
													Activo
												</p>
											</div>
											<div class="art-actions">
												<div class="form-group">
													<select class="form-control select-main" id="productStatus">
														<option>Acciones</option>
														<option>Ver</option>
														<option>Editar</option>
														<option>Eliminar</option>
													</select>
												</div>
											</div>
										</div>
										<div class="art-item">
											<div class="art-check">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input">
													<span class="custom-control-indicator"></span> </label>
											</div>
											<div class="art-img">
												<img src="img/1x1.png" alt="SKU">
											</div>
											<div class="art-title">
												<p>
													Smart TV LED UHD 4K 55". LG®
												</p>
											</div>
											<div class="art-price">
												<p>
													$123.456.00
												</p>
											</div>
											<div class="art-status">
												<p>
													Activo
												</p>
											</div>
											<div class="art-actions">
												<div class="form-group">
													<select class="form-control select-main" id="productStatus">
														<option>Acciones</option>
														<option>Ver</option>
														<option>Editar</option>
														<option>Eliminar</option>
													</select>
												</div>
											</div>
										</div>
										<div class="art-item">
											<div class="art-check">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input">
													<span class="custom-control-indicator"></span> </label>
											</div>
											<div class="art-img">
												<img src="img/1x1.png" alt="SKU">
											</div>
											<div class="art-title">
												<p>
													Smart TV LED UHD 4K 55". LG®
												</p>
											</div>
											<div class="art-price">
												<p>
													$123.456.00
												</p>
											</div>
											<div class="art-status">
												<p>
													Activo
												</p>
											</div>
											<div class="art-actions">
												<div class="form-group">
													<select class="form-control select-main" id="productStatus">
														<option>Acciones</option>
														<option>Ver</option>
														<option>Editar</option>
														<option>Eliminar</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
	<hr>
	<div class="row justify-content-end">
		<div class="col-sm-3">
			<button class="btn-main">
				Cerrar
			</button>
		</div>
		<div class="col-sm-3">
			<button class="btn-main">
				Guardar
			</button>
		</div>
	</div>

	</form>
</div>