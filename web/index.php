<?php 
	require_once "../app/Controlador.php";

	
	//utilizaré el enrutamiento para que me cague todas las páginas desde index!

	$fin=array('clase'=>'Controlador','menu'=>'error');
	session_start();
	if(!isset($_SESSION['lvl'])){$_SESSION['lvl']=0;}
	
	//el parseo de rutas tiene muchas opciónes, la mayoría de ellas tienen plantilla propia pero no todas, algunas sirven para realizar determinadas funciones, lo que si tienen todas es méto2 en el controlador, donde se explicará para que sirve cada uno de ellos!
	$menus=array(
		"inicio"=>array("clase"=>"Controlador","menu"=>"inicio"),
		"alta"=>array("clase"=>"Controlador","menu"=>"alta"),
		"exito"=>array("clase"=>"Controlador","menu"=>"exito"),		
		"error"=>array("clase"=>"Controlador","menu"=>"error"),
		"login"=>array("clase"=>"Controlador","menu"=>"login"),		
		"borrarCuenta"=>array("clase"=>"Controlador","menu"=>"borrarCuenta"),		
		"horasCotizadas"=>array("clase"=>"Controlador","menu"=>"horasCotizadas"),		
		"logoff"=>array("clase"=>"Controlador","menu"=>"logoff"),
		
		
		
	);
	///IMPORTANTE!!!!!!!
	//CONTROLAMOS QUE NINGÚN USUARIO CON NIVEL "0 = INVITADO" PUEDA TOCAR NINGÚN MENÚ EXCEPTO LOGIN Y REGISTRO
	
	if($_SESSION['lvl']!=0){
		if(isset($_GET['dir'])){
			if(isset($menus[$_GET['dir']])){
				$fin=$menus[$_GET['dir']];
			}
		}else{
			$fin=array('clase'=>'Controlador','menu'=>'error');
		}
	}else{
		if(isset($_GET['dir'])){
			if($_GET['dir']=='alta')$fin=array('clase'=>'Controlador','menu'=>'alta');
			else $fin=array('clase'=>'Controlador','menu'=>'login');
		}else{$fin=array('clase'=>'Controlador','menu'=>'login');}
		
	}
		

	//si exíste método parseado llamamos a método parseado, sino....
	if(method_exists($fin['clase'],$fin['menu'])){
		call_user_func(array(new $fin['clase'],$fin['menu']));
	}
		


 ?>	