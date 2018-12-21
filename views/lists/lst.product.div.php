<?php 
global $Session;
$prd = $record;
?>
		<div class="art-item">
			<div class="art-check">
				<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input list-chk" id='chk_<?php echo $record['id_product'] ?>' value='<?php echo $record['id_product'] ?>' >
					<span class="custom-control-indicator"></span> 
				</label>
			</div>
			<div class="art-img">
				<img src="img/1x1.png" alt="<?php echo $record['prd_sku'] ?>">
			</div>
			<div class="art-sku">
				<p> <?php echo $record['prd_sku'] ?> </p>
			</div>
			<div class="art-title">
				<p> <?php echo $record['prd_label'] ?> </p>
			</div>
			<div class="art-model">
				<p> <?php echo $record['prd_model'] ?> </p>
			</div>
			<div class="art-brand">
				<p> <?php echo $record['brd_name'] ?> </p>
			</div>
			<div class="art-price">
				<p> $ <?php echo number_format( $record['ppr_public_price'], 2 ) ?> </p>
			</div>
			<div class="art-actions">
				<div class="form-group">
					<select class="form-control select-main" id="inp_productStatus_<?php echo $record['id_product'] ?>">
						<option>Acciones</option>
						<option>Ver</option>
						<option>Editar</option>
						<option>Eliminar</option>
					</select>
				</div>
			</div>
		</div>