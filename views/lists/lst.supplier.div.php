<?php 
global $Session;
$sup = $record;
?>
		<div class="art-item">
			<div class="art-check text-center" <?php echo ( isset($this->columns[0]['width']) && $this->columns[0]['width'] != '' ? " style='width:" . $this->columns[0]['width'] . ";'" : "" ) ?> >
				<label class="custom-control custom-checkbox text-center">
					<input type="checkbox" class="custom-control-input list-chk" id='chk_<?php echo $record['id_supplier'] ?>' value='<?php echo $record['id_supplier'] ?>' >
					<span class="custom-control-indicator"></span> 
				</label>
			</div> 
			<div class="art-name" <?php echo ( isset($this->columns[1]['width']) && $this->columns[1]['width'] != '' ? " style='width:" . $this->columns[1]['width'] . ";'" : "" ) ?> >
				<p> <?php echo $record['sup_code'] ?> </p>
			</div>
			<div class="art-name" <?php echo ( isset($this->columns[2]['width']) && $this->columns[2]['width'] != '' ? " style='width:" . $this->columns[2]['width'] . ";'" : "" ) ?> >
				<p> <?php echo $record['sup_name'] ?> </p>
			</div>
			<div class="art-rfc" <?php echo ( isset($this->columns[3]['width']) && $this->columns[3]['width'] != '' ? " style='width:" . $this->columns[3]['width'] . ";'" : "" ) ?> >
				<p> <?php echo $record['sup_RFC'] ?> </p>
			</div>
			<div class="art-active text-center" <?php echo ( isset($this->columns[4]['width']) && $this->columns[4]['width'] != '' ? " style='width:" . $this->columns[4]['width'] . ";'" : "" ) ?> >
			<p> <?php echo $record['sup_active'] > 0 ? "&#10004;" : " X " ?> </p>
			</div> 
			<div class="art-actions" <?php echo ( isset($this->columns[5]['width']) && $this->columns[5]['width'] != '' ? " style='width:" . $this->columns[5]['width'] . ";'" : "" ) ?> >
				<div class="form-group">
					<select class="form-control select-main" id="inp_supplier_actions_<?php echo $record['id_supplier'] ?>" onchange='action_supplier(this)'>
						<option value='0'>Acciones</option>
						<option value='1'>Ver</option>
						<option value='2'>Editar</option>
						<option value='3'>Eliminar</option>
					</select>
				</div>
			</div>
		</div>