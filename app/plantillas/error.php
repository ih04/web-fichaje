<?php ob_start(); ?>
<h1>La página solicitada anteriormente está  en proceso de reforma!</h1>
<?php if(isset($_REQUEST['err']))echo "<h3>".$_REQUEST['err']."</h3>"; ?>
<?php $buffer= ob_get_clean(); include "head.php"; ?>
