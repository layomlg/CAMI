<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" /> 
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Titulo</title>
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
    <nav class="navbar navbar-expand fixed-top">
      <a class="navbar-brand" href="#"><img src="img/svg/logo.svg"></a>
      <div class="navbar-collapse">
        <div class="mt-2 mt-md-0 ml-auto">
          <img src="img/svg/alert.svg">
          <img src="img/svg/profile.svg">
        </div>
      </div>
    </nav>
    <!-- /Barra de Navegacion --> 
    <!-- Contenedor General -->
    <div class="contenedor">
      <div class="sidebar">
        <ul class="sidebar-nav">
          <li>
            <a href="#">Prospectos</a>
          </li>
          <li>
            <a href="#">Estadística</a>
          </li>
          <li>
            <a href="#">Información MLG</a>
          </li>
          <li>
            <a href="#">Calendario</a>
          </li>
        </ul>
      </div>
      <div class="cuerpo">
        <div class="bloque"></div>
        <div class="bloque"></div>
      </div>
    </div>
    <!-- /Contenedor General -->

    <!-- Footer -->
    <!-- /Footer -->
    <div class="footer">
    </div>
  </body>
</html>