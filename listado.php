<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" /> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<title>CAMI || Plantillas</title>
		<link href="img/favicon.ico" rel="shortcut icon" />
		<!-- CSS -->
		<link href="libs/bootstrap/css/bootstrap.css?v=<?php echo filemtime( "libs/bootstrap/css/bootstrap.css" )?>"	type="text/css"	rel="stylesheet" />
		<link href="libs/fontawesome/css/fontawesome.css?v=<?php echo filemtime( "libs/fontawesome/css/fontawesome.css" )?>"	type="text/css"	rel="stylesheet" />
		<link href="css/style.css?v=<?php echo filemtime( "css/style.css" )?>"	type="text/css" rel="stylesheet" /> 
		<!-- /CSS -->
		<!-- JS -->
		<script src="js/jquery-3.3.1.min.js?v=<?php echo filemtime( "js/jquery-3.3.1.min.js" )?>"	type="text/javascript"></script>
		<script src="js/popper.min.js?v=<?php echo filemtime( "js/popper.min.js" )?>"	type="text/javascript"></script>
		<script src="js/tooltip.min.js?v=<?php echo filemtime( "js/tooltip.min.js" )?>"	type="text/javascript"></script>
		<script src="libs/bootstrap/js/bootstrap.js?v=<?php echo filemtime( "libs/bootstrap/js/bootstrap.js" )?>"	type="text/javascript"></script>
		<script src="libs/fontawesome/js/fontawesome.js?v=<?php echo filemtime( "libs/fontawesome/js/fontawesome.js" )?>"	type="text/javascript"></script>
		<script src="libs/jquery-ui/jquery-ui.js?v=<?php echo filemtime( "libs/jquery-ui/jquery-ui.js" )?>"	type="text/javascript"></script>

		<!-- /JS -->
	</head> 
	<body > 
		<!-- ERRORES Y MENSAJES --> 
		<!-- /ERRORES Y MENSAJES -->
		<!-- Barra de Navegacion -->        
		<?php
	require_once("views/layout/header.php");    
		?>
		<!-- /Barra de Navegacion --> 
		<!-- Contenedor General -->
		<div class="main" id="main">
			<?php
			require_once("views/layout/sidebar.php");    
			?>
			<div class="content" id="content-container">
				<div class="block">
					<p class="title">Productos</p>
					<div class="row">
						<div class="col-sm-4">
							<div class="card">
								<p>Activos</p>
								<span><span></span></span>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="card">
								<p>Inactivos</p>
								<span><span></span></span>
							</div>
						</div>
						<div class="col-sm-4">
							<button class="btn-main">Individual</button>
							<button class="btn-main">Excel</button>
						</div>
					</div>
				</div>
				<div class="block">
					<p class="title">Productos</p>
					<p class="subtitle">Filtro por:</p>
					<div class="row">
						<div class="col-sm-4">
							<select name="" id="" class="select-main">
								<option value="">Rango de precios</option>
								<option value="">Rango 1</option>
								<option value="">Rango 2</option>
							</select>
						</div>
						<div class="col-sm-4">
							<select name="" id="" class="select-main">
								<option value="">Categoría</option>
								<option value="">Categoría 1</option>
								<option value="">Categoría 2</option>
							</select>
						</div>
						<div class="col-sm-4">
							<select name="" id="" class="select-main">
								<option value="">Marca</option>
								<option value="">Marca 1</option>
								<option value="">Marca 2</option>
							</select>
						</div>
					</div>
					<div>
						<table class="table">
							<thead>
								<tr>
									<th colspan="2">IMG</th>
									<th>Título</th>
									<th>Precio</th>
									<th>Estatus</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6">No se encontraron coincidencias</td>
								</tr>
								<tr>
									<td><input type="checkbox"></td>
									<td><img class="art" src="img/1x1.png"></td>
									<td>Lorem ipsum</td>
									<td>$123,456.00</td>
									<td>Activo</td>
									<td>
										<select name="" id="" class="select-main">
											<option value="">Acciones</option>
											<option value="">Accion 1</option>
											<option value="">Accion 2</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="checkbox"></td>
									<td><img class="art" src="img/1x1.png"></td>
									<td>Lorem ipsum</td>
									<td>$123,456.00</td>
									<td>Activo</td>
									<td>
										<select name="" id="" class="select-main">
											<option value="">Acciones</option>
											<option value="">Accion 1</option>
											<option value="">Accion 2</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="checkbox"></td>
									<td><img class="art" src="img/1x1.png"></td>
									<td>Lorem ipsum</td>
									<td>$123,456.00</td>
									<td>Activo</td>
									<td>
										<select name="" id="" class="select-main">
											<option value="">Acciones</option>
											<option value="">Accion 1</option>
											<option value="">Accion 2</option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>            
					</div>
					<div>
						<div class="row">
							<div class="art"></div>
						</div>


					</div>
					<div>
						<p>Modificar seleccionados:</p>
						<select name="" id="" class="select-main">
							<option value="">Acciones seleccionadas</option>
							<option value="">Acción 1</option>
							<option value="">Acción 2</option>
						</select>
					</div>
					<div>
						<p>Agregar prospecto:</p>
						<button class="btn-main">Individual</button>
						<button class="btn-main">Excel</button>
					</div>
				</div>
				<?php
				require_once("views/layout/footer.php");    
				?>
			</div>
		</div>
		<!-- /Contenedor General -->
		<!-- Footer -->

		<!-- /Footer -->
		<script type="text/javascript" src="js/default.js?v=<?php echo filemtime( "js/default.js" )?>"></script>
	</body>
</html>