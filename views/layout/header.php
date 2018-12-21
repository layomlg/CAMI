<nav class="navbar navbar-expand fixed-top">
  	<button type="button" 
		class="navbar-toggle">
		<i class="fa fa-bars fa-2x"></i>
	</button>
  <div class="navbar-collapse">
    <div class="mt-2 mt-md-0 ml-auto">
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item dropdown dropleft">
		        <a class="nav-link dropdown-toggle" href="#" 
		        	id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" 
		        	aria-haspopup="true" aria-expanded="false">
		        	<img src="img/svg/alert.svg">
		        	<span class="badge badge-primary">3</span>
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
		          <a class="dropdown-item ntf-request" href="#">Solicitud: Se registro un nueva solicitud de catalogo.</a>
		          <a class="dropdown-item ntf-change" href="#">Cambio: Se realizo un cambio de precio en un producto.</a>
		          <a class="dropdown-item ntf-new" href="#">Nuevo: Se genero un nuevo catalogo.</a>
		        </div>
			</li>
			<li class="nav-item dropdown dropleft">
		        <a class="nav-link dropdown-toggle" href="#" 
		        	id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" 
		        	aria-haspopup="true" aria-expanded="false">
		        	<img src="img/svg/profile.svg">
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
		          <a class="dropdown-item" href="#"><?php //echo $Session -> get_email(); ?></a>
		          <a class="dropdown-item" href="logout.php">Cerrar Sesi&oacute;n</a>
		        </div>
			</li>
		</ul>
  	</div>
  </div>
</nav>
<style type="text/css">
	div.navbar-collapse li div.dropdown-menu a.dropdown-item{
    	padding: 15px;
    	opacity: 0.75;
	}
	div.navbar-collapse li div.dropdown-menu a.dropdown-item:hover{
    	opacity: 1;
	}
	div.navbar-collapse li div.dropdown-menu a.ntf-request{
		background-color: lightgreen;
	}
	div.navbar-collapse li div.dropdown-menu a.ntf-change{
		background-color: lightyellow;
	}
	div.navbar-collapse li div.dropdown-menu a.ntf-new{
		background-color: lightblue;
	}
	div.navbar-collapse li a.dropdown-toggle span.badge{
		position: absolute;
    	bottom: -1px;
    	right: 16px;
	}
	.sidebar{
		padding: 25px 0 70px 0;
	}
	.sidebar img{
		margin: 0 auto;
    	margin-bottom: 25px;
    	display: block;
	}
	.navbar-toggle{
		cursor: pointer;
		margin-left: 25px;
	}
</style>