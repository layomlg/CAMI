<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo SYS_TITLE;?></title>
	<link href="img/favicon.ico" rel="shortcut icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="libs/fontawesome/css/fontawesome.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Jquery JS -->
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <!-- Popper JS -->
	<script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Tooltip JS -->
	<script type="text/javascript" src="js/tooltip.min.js"></script>
    <!-- Bootstrap JS -->
	<script type="text/javascript" src="libs/bootstrap/js/bootstrap.min.js"></script>

  </head>
  <body>
  	<div class="container-fluid h-100">
  		<div class="row align-items-center justify-content-center h-100">
  			<div class="col-4">
  				<form action="login.php" method="post" >
  					<fieldset>
  						<?php if( !empty($error) ){ ?>
  						<div class="alert alert-warning" role="alert">
  							<h4 class="alert-heading">Error!</h4>
							<p><?php echo $error;?></p>
						</div>
						<?php } ?>
  						<div class="form-group text-center">
							<img src='img/logo_mlg_200.png' alt="MLG" class="img-responsive" style=" margin: 20px auto;" />
						</div>
  						<div class="form-group">
							<label for="login_email">Correo</label>
							<input type="email" class="form-control required" id="login_email" name="login_email"
								aria-describedby="emailHelp" placeholder="Ingresa correo" required="" />
						</div>
						<div class="form-group">
							<label for="login_pass">Contraseña</label>
							<input type="password" class="form-control" id="login_pass" name="login_pass" 
								placeholder="Contraseña" required="" />
						</div>
						<div class="form-group text-right">
							<small>¿Olvidaste tu contrase&ntilde;a? <a href="#" >Click aqu&iacute;</a></small>
						</div>
						<button type="submit" class="btn btn-primary">Ingresar</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
</html>