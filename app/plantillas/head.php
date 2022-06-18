<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	
	<link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap-5.0.2-dist/js/bootstrap.bundle.js">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="contenedorMenuLogo container-fluid  p-5 ">
		<div class="padreLogo row">
   
		<!-- LOGO -->
			<div class="divLogo col-12 col-md-3">
				<img class="logo" src="imagenes/logo1tra.png" alt="">
			</div>

		<!-- MENÚ -->
			<?php if($_SESSION['lvl']>0): ?>
			<div class="contenedorMenu   container-fluid col-12 col-md-9">
			    <!-- BARRA NAVEGACIÓN -->
			    <div class="menu  bg-light ">
					<nav class="navbar  navbar-expand-md navbar-light  border-3 border-bottom border-primary colorMenu">
	            		<div class="container-fluid  ">
	                
	               			 <div id="MenuNavegacion" class="navbar-collapse  collapse show" style="">
							<ul class="navbar-nav ms-3">
								<li class="nav-item">
		                       		 <a class="nav-link active" href="index.php?dir=inicio">Inicio</a>
			                    </li>
								<li class="nav-item">
			                        <a class="nav-link" href="index.php?dir=horasCotizadas">Mis cotizaciones</a>
			                    </li>
								
			                    <?php if($_SESSION['lvl']==2): ?>
			                    	<li class="nav-item">
			                        <a class="nav-link" href="index.php?dir=alta">Registrar usuario</a>
			                     <?php endif; ?>
			                    </li>
			                   
			                    <?php if($_SESSION['lvl']==2): ?>
			                    	<li class="nav-item">
			                        <a class="nav-link" href="index.php?dir=borrarCuenta">Borrar usuario</a>
			                    <?php endif; ?>    
			                    </li>
			                    
			                    <li class="nav-item">
			                        <a class="nav-link" href="index.php?dir=logoff">Finalizar sesión</a>
			                    </li>
	                    
							</ul>
	                <!-- <ul class="navbar-nav ms-3">
						<li class="nav-item">
	                        <a class="nav-link text-nowrap" href="#">Iniciar sesión</a>
	                    </li>
	                </ul> -->
							 </div>
							 		<button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#MenuNavegacion" aria-expanded="">
					                        <span class="navbar-toggler-icon"></span>
					                </button>


					                <div class="perfilHeader">	

										<?php  
											if(isset($_SESSION['username'])){
												// echo $_SESSION['username']; 
												echo $_SESSION['nombre']; 
												echo " ".$_SESSION['apellidos']; 
												echo "<br>Soy: ";	
												if($_SESSION['lvl']==1){echo "Empleado";} 
												if($_SESSION['lvl']==2){echo "Administrador";}}
										?>
					

									</div>


					   		 </div>

								
						</nav>
					</div>

  

  					<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

  					

			</div>	
			<?php endif; ?>


		<!-- <?php if($_SESSION['lvl']>0): ?>

		<header class="header col-12 col-md-6">
			<div class="celdas"><a href="index.php?dir=inicio">Inicio</a></div>
			<?php if($_SESSION['lvl']>=1): ?><div class="celdas"><a href="index.php?dir=alta">regístrate</a></div><?php endif; ?>
			<?php if($_SESSION['lvl']==0): ?><div class="celdas"><a href="index.php?dir=login">login</a></div><?php endif; ?>
			
			
			<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=horasCotizadas">mis cotizaciones</a></div><?php endif; ?>
			<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=logoff">finalizar sesion</a></div><?php endif; ?>

		
		
		</header>
		<?php endif; ?>	 -->

		</div>
	</div> 
	

	

	
	<br>
	<br>
	<br>

	
	<?php echo $buffer; ?>



</body>
</html>