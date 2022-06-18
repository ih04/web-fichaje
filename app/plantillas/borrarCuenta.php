


<form class=" centrar" action="" method="POST">
	<div class="form-group col-md-3 centrar">
		<div class="textoHTML"><h4>borrar usuario</h4></div>
	 	<input class=" form-control" type="text" name="username">
	</div>
	
	

	<div class="textoHTML">aceptar<input type="checkbox" name="check"></div>
	<input class="btn btn-dark margen" type="submit" name="send" value="adelante">

</form>

<?php $buffer=ob_get_clean(); include "head.php";?>