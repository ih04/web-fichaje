<?php ob_start(); ?>


<!-- <div class="d-grid gap-2">
  <button class="btn btn-primary" type="button">Button</button>
  <button class="btn btn-primary" type="button">Button</button>
</div> -->
<form action="" class="d-grid gap-2 col-6 mx-auto" method="post">
	<input  type="submit" class="btn btn-primary" name="entrada" value="entrada">
	

</form>
<form action="" class="d-grid gap-2 col-6 mx-auto margen" method="post">
	<input  type="submit" class="btn btn-secondary" name="salida" value="salida">
</form>

<!-- <button type="button" class="btn btn-primary">boton</button> -->

<?php $buffer = ob_get_clean();  include "head.php";?>
