
<?php ob_start(); ?>
<form class="centrar">
  <div class="form-group col-md-3 centrar">
    <label for="formGroupExampleInput">Usuario:</label>
    <input type="text" class="form-control campo" name="username" id="formGroupExampleInput" >
  </div>
  <div class="form-group col-md-3 centrar margen">
    <label for="formGroupExampleInput2">Contraseña:</label>
    <input type="text" class="form-control campo" name="contrasena" id="formGroupExampleInput2" >
    
  </div>
  <input class="btn btn-dark margen" value="Entrar" type="submit" name="send">
  <br>
<!--   <a  href="index.php?dir=alta" > <b>Regístrate</b> </a>si lo necesitas
 --></form>
 <br>

<!-- <form class="formulario" action="" method="post" >
	<div class="textoHTML"></div>
	<input class="campo form-control"  type="text" name="username">
	<div class="textoHTML">contraseña:</div>
	<input class="campo" type="text" name="contrasena"><br>
	<input class="boton" type="submit" name="send">
</form> -->







<?php $buffer=ob_get_clean(); include("head.php");?>

