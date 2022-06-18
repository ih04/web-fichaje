<?php 
	//funcion para retrasarel tiempo

	function timeOut($segun2){
			$actual=time();
		 	$despues=$actual+$segun2;

		 	do{}while(time()<$despues);

	 	

	}

	

 ?>