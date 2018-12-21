<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'init.php';

global $mensaje, $error;

if (!empty($_GET['msg'])) $mensaje .= $_GET['msg'];
if (!empty($_GET['err'])) $error .= urldecode($_GET['err']); 

$msg_class = "oculto";
$err_class = "oculto";

if ( !empty($mensaje) ) {
	$msg_class = "";
	$timer = "mensaje";
} 
if ( !empty($error) ){
	$err_class = "";
	$timer = "error";
}

if ( !$Session->logged_in() ){
	require_once DIRECTORY_VIEWS.'forms/frm.login.php';
	die();
}

if( !empty($_GET['rsrc']) ){
	if( !$Session->validPermissions() ){
		header("Location: index.php?error=Acces denied: Permissions");
		die();
	}
	$Index->logic(strip_tags(trim($_GET['rsrc'])));
}

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" /> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<title><?php echo $Index->get_title(); ?></title>
		<link href="img/favicon.ico" rel="shortcut icon" /> 
		<!-- CSS -->
		<!-- Bootstrap CSS -->
		<link type="text/css" rel="stylesheet" 
			  href="libs/bootstrap/css/bootstrap.min.css?v=<?php echo filemtime( "libs/bootstrap/css/bootstrap.css" )?>">
		<!-- Fontawesome CSS -->
		<link type="text/css" rel="stylesheet" 
			  href="libs/fontawesome/css/all.min.css?v=<?php echo filemtime( "libs/fontawesome/css/all.min.css" )?>">
		<!-- CSS -->
		<link type="text/css" rel="stylesheet" 
			  href="css/style.css?v=<?php echo filemtime( "css/style.css" )?>">

		<?php echo $Index->get_css(); ?> 
		<!-- /CSS -->
		<!-- JS -->
		<!-- Jquery JS -->
		<script type="text/javascript" src="js/jquery-3.3.1.min.js?v=<?php echo filemtime( "js/jquery-3.3.1.min.js" )?>"></script>
		<!-- Popper JS -->
		<script type="text/javascript" src="js/popper.min.js?v=<?php echo filemtime( "js/popper.min.js" )?>"></script>
		<!-- Tooltip JS -->
		<script type="text/javascript" src="js/tooltip.min.js?v=<?php echo filemtime( "js/tooltip.min.js" )?>"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="libs/bootstrap/js/bootstrap.min.js?v=<?php echo filemtime( "libs/bootstrap/js/bootstrap.min.js" )?>"></script>

		<script type="text/javascript" src="js/default.js?v=<?php echo filemtime( "js/default.js" )?>"></script>

		<?php echo $Index->get_js(); ?>

		<!-- /JS -->
	</head>
	<body >
		<?php 
		require_once 'views/layout/header.php'
		?>
		<div id="main">
			<div class="sidebar" id="sidebar-container">
				<img src="img/svg/logo.svg" width="100" />
				<?php echo $Index->get_menu();  ?>
			</div>
			<div class="content" id="content-container">
				<?php require_once $Index->get_content(); ?>
				<?php require_once 'views/layout/footer.php'; ?>
			</div>
		</div> 
	</body>
</html>