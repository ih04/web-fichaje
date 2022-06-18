<?php ob_start(); ?>
<form class=" centrar" action="index.php?dir=alta" method="POST" enctype="multipart/form-data">
	<div class="form-group col-md-3 centrar">
		<div class="">*Nombre:</div>
	 	<input class=" form-control" type="text" name="nombre">
	</div>
	<div class="form-group col-md-3 centrar">
		<div class="">*Apellidos:</div>
	 	<input class=" form-control" type="text" name="apellidos">
	</div>
	<div class="form-group col-md-3 centrar">
		<div class="">*Username:</div>
	 	<input class=" form-control" type="text" name="username">
	</div>
	<div class="form-group col-md-3 centrar margen">
		<div class="">*Contraseña:</div> 
		<input class="campo form-control" type="text" name="contrasena">
	</div>
	<div class="form-group col-md-3 centrar margen">
		<div class="">*Email:</div> 
		<input class="campo form-control" type="text" name="mail">
	</div>

	
	

	
	
		<input class="btn btn-dark margen" type="submit" value="Enviar" name="send"><br>

		<!-- <a href="index.php?dir=login"> <b>Logueate</b> </a>si ya tienes una cuenta -->

</form>

	los campos marcados con <b class="asterisco errores">*</b> son obligatorios!
	
<?php $buffer= ob_get_clean();
	include "head.php";
 ?>