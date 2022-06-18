<?php ob_start(); ?>


<?php  if(isset($_REQUEST['ex']))echo "<h3 class=\"centrar\">".$_REQUEST['ex']."</h3>"; ?>

<?php $buffer=ob_get_clean(); include "head.php"; ?>
