<?php 
global $catalogue;

$id_supplier = isset( $_GET['id'] ) && is_numeric($_GET['id']) && $_GET['id'] > 0 ? $_GET['id'] : 0 ;

require_once DIRECTORY_CLASS . "class.supplier.php";
$supplier = new Supplier( $id_supplier );

?>
<div class="block">
	<form id="frm_supplier" action="supplier.php" method="post" class="form-data" >
		<input type='hidden' id='inp_id_supplier' name='id_supplier' value='<?php echo $supplier->id_supplier ?>' />
		<input type='hidden' id='inp_cb' name='cb' value='<?php echo SUPPLIERS ?>' />
		<div class="row">
			<div class="col-sm-6"> <h3 class="title"> Edición de Proveedor </h3> </div>
			<div class="col-sm-2">
				<button type='button' class="btn-main" onclick="location.href='?rsrc=<?php echo SUPPLIERS ?>'"> Cancelar </button>
			</div>
			<div class="col-sm-2">
				<button class="btn-main" onclick="$('#inp_cb').val('<?php echo SUPPLIER_FRM ?>');"> Guardar y continuar </button>
			</div>
			<div class="col-sm-2">
				<button class="btn-main" onclick="$('#inp_cb').val('<?php echo SUPPLIERS ?>');"> Guardar y regresar </button>
			</div>
		</div>
		<div class="row"> 
			<div class="col-sm-4" >
				<div class="input-group">
					<label for="inp_code">IdProveedor</label>
					<input type="text" class="form-control" id="inp_code" name="code" accept="inp_code_help" placeholder="Ingresa una código" value='<?php echo $supplier->code ?>'/>
					<div id="inp_code_help" class="invalid-feedback">
						Por favor ingresa un código válido
					</div> 
				</div>
			</div>
			<div class="col-sm-8">
				<div class="input-group">
					<label for="inp_name">Nombre</label>
					<input type="text" class="form-control" id="inp_name" name="name" aria-describedby="inp_name_help" placeholder="Ingresa un nombre" value='<?php echo $supplier->name ?>'/> 
					<div id="inp_name_help" class="invalid-feedback">
						Por favor ingresa un nombre válido
					</div>
				</div> 
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="input-group">
					<label for="inp_rfc">RFC</label>
					<input type="text" class="form-control" id="inp_rfc" name="rfc" accept="inp_code_help" placeholder="Ingresa un RFC" value='<?php echo $supplier->RFC ?>' />
					<div id="inp_rfc_help" class="invalid-feedback">
						Por favor ingresa un RFC válido
					</div> 
				</div>
			</div>
			<div class="col-sm-4">
				<div class="input-group">
					<label for="inp_id_type">Tipo</label>
					<select type="text" class="form-control select-main" id="inp_id_type" name="id_type" accept="inp_id_type_help" placeholder="Selecciona un tipo" >
						<?php echo $catalogue->get_catalogue_options( 'supplier_type', $supplier->id_supplier_type ); ?>
					</select>
					<div id="inp_id_type_help" class="invalid-feedback">
						Por favor selecciona un Tipo de Proveedor.
					</div> 
				</div>
			</div>
			<div class="col-sm-4"> 
				<div class="input-group">
					<label for="inp_active">Status</label>
					<select class="form-control select-main" id="inp_active">
						<option value='1' <?php echo $supplier->active > 0 ? "selected='selected'" : "" ?> >Activo</option>
						<option value='0' <?php echo $supplier->active > 0 ? "" : "selected='selected'" ?> >Inactivo</option>
					</select> 
					<div id="inp_active_help" class="invalid-feedback" >
						Por favor ingresa el status.
					</div>
				</div>
			</div> 
		</div>
		<hr>
		<div class="col-sm-12">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" href="#additional" role="tab" data-toggle="tab"> Adicional </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#address" role="tab" data-toggle="tab"> Direcciones </a>
				</li> 
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active show" id="additional">
					<div class="row">
						<div class="col-sm-4">
							<div class="input-group">
								<label for="inp_credit_days">Días de crédito</label>
								<input type="number" class="form-control text-center" id="inp_credit_days" id="credit_days" aria-describedby="inp_credit_days_help" 
									placeholder="Ingresa los días de crédito" value='<?php echo $supplier->additional_info->credit_days ?>' />
								<div class="input-group-append"> <button type="button" class="btn" > días </button> </div> 
								<div id="inp_credit_days_help" class="invalid-feedback">
									Por favor ingresa los días de crédito
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="input-group">
								<label for="inp_credit_limit">Límite de Crédito </label>
								<div class="input-group-append"> <button type="button" class="btn" > $ </button> </div> 
								<input type="number" step="any" class="form-control text-right" id="inp_credit_limit" name='credit_limit' aria-describedby="inp_credit_limit_help" 
									placeholder="Ingresa el límite de crédito"  value='<?php echo $supplier->additional_info->credit_limit ?>' /> 
								<div id="inp_credit_limit_help" class="invalid-feedback">
									Por favor ingresa el límite de crédito
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="input-group">
								<label for="inp_minimum_purchase">Cómpra mínima</label>
								<input type="number" class="form-control text-center" id="inp_minimum_purchase" id="minimum_purchase" aria-describedby="inp_minimum_purchase_help" 
									placeholder="Ingresa los días de crédito"  value='<?php echo $supplier->additional_info->minimum_purchase ?>' />
								<div class="input-group-append"> <button type="button" class="btn" > pzas </button> </div> 
								<div id="inp_minimum_purchase_help" class="invalid-feedback">
									Por favor ingresa un número mínimo de compra
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="input-group">
								<label for="inp_delivery_time">Tiempo de entrega</label>
								<input type="number" class="form-control text-center" id="inp_delivery_time" id="delivery_time" aria-describedby="inp_delivery_time_help" 
									placeholder="Ingresa el tiempo de entrega"  value='<?php echo $supplier->additional_info->delivery_time ?>' />
								<div class="input-group-append"> <button type="button" class="btn" > días </button> </div>
								<div id="inp_delivery_time_help" class="invalid-feedback">
									Por favor ingresa una categoria
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="input-group">
								<label for="inp_delivery_type">Tipo de entrega</label>
								<select class="form-control select-main" id="inp_delivery_type" name='delivery_type' >
									<?php echo $catalogue->get_catalogue_options( 'delivery_type', $supplier->additional_info->id_delivery_type ); ?>
								</select> 
								<div id="productBrandHelp" class="invalid-feedback">
									Por favor selecciona un tipo de entrega
								</div>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="address" >
					<div class="row" style="min-height: 115px;">
						<div class="col-sm-12">
							<h3> Direcciones </h3>
							<div class='row'>
								<div class='hidden-xs col-sm-8'> &nbsp; </div>
								<div class='col-sm-4'>
									<button type='button' class='btn-main' onclick="edit_supplier_address(0);"> Agregar Dirección </button>
								</div>
							</div>
							<div class='row' id='supplier_addresses'>
								<ul>
								<?php 
								foreach ($supplier->address as $k => $addr ) {
									echo "<li> <b> $addr->address_type </b>: <br/> " 
											. " $addr->street, <br/> " 
											. " $addr->locality, $addr->district <br/> "
											. " $addr->zipcode, $addr->country <br/> "
											. " <button type='button' onclick='edit_supplier_address($addr->id_address);'  > Editar </button> "
											. " <button type='button' onclick='delete_supplier_address($addr->id_address);'> Borrar </button> " 
											. " </li>";
								}
								?>
								</ul>
							</div>
						</div> 
					</div> 
				</div>
			</div>
		</div>
		<div class='row'> &nbsp; </div>
		<hr>
		<div class='row'> &nbsp; </div>
		<div class="row justify-content-end">
			<div class="col-sm-2">
				<button type='button' class="btn-main" onclick="location.href='?rsrc=<?php echo SUPPLIERS ?>'"> Cancelar </button>
			</div>
			<div class="col-sm-2">
				<button class="btn-main" onclick="$('#inp_cb').val('<?php echo SUPPLIER_FRM ?>');"> Guardar y continuar </button>
			</div>
			<div class="col-sm-2">
				<button class="btn-main" onclick="$('#inp_cb').val('<?php echo SUPPLIERS ?>');"> Guardar y regresar </button>
			</div>
		</div>
	</form>
</div>