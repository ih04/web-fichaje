<?php 
	require "../app/libs/bGeneral.php";
	require "../app/libs/utils.php";
	require "../app/Config.php";
	require "../app/Conexion.php";


	class Controlador{
		
		var $errores=[];
		 
		public function inicio(){
		  
			//ENTRADA
			$db=Conectate::getInstance();
			$idUsr=$_SESSION['idUsr'];
			$t=time();
			$d=date("d-m-Y/h:i",time());
				
			if(isset($_REQUEST['entrada'])){		
				
				if(isset($db->selecSalida($idUsr)[0][0]) || count($db->horasMes($idUsr))==0 || $db->superaHora($idUsr)==false){
					
					if($cons=$db->insertarEntrada($_SESSION['idUsr'],$t,$d)){?><script>alert("Entrada fichada!");</script><?php }else{
					?><script>alert("la entrada no se ha podido fichar debido a problemas de conexión");</script><?php
					 }

				}else{
					?><script>alert("No se puede fichar entrada hasta haber fichado salida previamente, o hasta haber transcurrido 24 horas");</script><?php
				}
			

				// echo date("h:i",1648540925);
				// echo date("h:i",time());
			}

			//SALIDA

			if(isset($_REQUEST['salida'])){
				
				if(count($db->horasMes($idUsr))>0 && isset($db->selecEntrada($idUsr)[0][0]) && !isset($db->selecSalida($idUsr)[0][0]) && $db->superaHora($idUsr)){
						
					if($cons=$db->modSalida($_SESSION['idUsr'],$t,$d)){?><script>alert("Salida fichada!");</script><?php }else{
					?><script>alert("la salida no se ha podido fichar debido a problemas de conexión");</script><?php
					 }
					

				}else{
					?><script>alert("No puedes fichar salida hasta haber fichado entrada!"); </script><?php

				}
				
				
				
			}
		  
			include "plantillas/inicio.php";
		  
		}



		//típico successful
		public function exito(){
			include "plantillas/exito.php";
		}

		

		// opción de borrar tu cuenta de la base de datos si estás en ella
		// public function borrarCuenta(){
		// 	ob_start();
		// 	if(isset($_REQUEST['send'])){
		// 		if(isset($_REQUEST['check'])){
		// 			$db=Conectate::GetInstance();
		// 			if(isset($_SESSION['idUsr'])){
		// 				$idUsr=$_SESSION['idUsr'];
		// 				if(count($db->insert("update usuario set contrasena='@@@@@@@@@@@234234____',usuario='borrado' where id=$idUsr;")>0)){
							

		// 					header("location:index.php?dir=logoff");


		// 				}else{
		// 					header("location:index.php?dir=error&err=Nan usuario");
		// 				} 
						
		// 			}
					




		// 		}else{
		// 			header("location:index.php?dir=error&err=algo salió mal");
			

			
		// }}include("plantillas/borrarCuenta.php");}

		
		
		public function error(){
			include "plantillas/error.php";
		}

		public function logoff(){
			session_destroy();
			header("location:index.php?dir=inicio");
		}


		//tabla con las cotizaciones por día / mes

		public function horasCotizadas(){
		  		
			$db=Conectate::getInstance();
			$idUsr=$_SESSION['idUsr'];

			if(count($this->errores)>0){
				foreach($this->errores as $e){
					echo '<p class="errores">'.$e."</p>";
				}
			}else{
				ob_start();
				
				//tabla con horas
				
				$arr=$db->horasMes($idUsr);
				
				//TABLA 1 horas
				echo "<table class=\"table table-hover table-dark\" name=\"copiame\">
						<tr>
							<th scope=\"col\">fecha entrada
							</th>
							<th scope=\"col\">fecha salida
							</th>
							<th scope=\"col\">horas 
							</th>
							
						</tr>";

				
				//print_r($arr);

				//horas
				$horasD=0;
				//dias
				$dias="--";
				

				$mismoDia="--";

				
				
				foreach($arr as $e){

					$in=date("d-m-Y H:i",$e['SEntrada']);

					//comprobamos si estamos en el mismo día
					if($mismoDia!==($hoy=explode(" ",$in)[0])){
						$mismoDia=$hoy;
						$horasD=-3600;
						
					}

				
					

					if(!isset($e['SSalida'])){$out="--";}else{$out=date("d-m-Y H:i",$e['SSalida']);


					}
					//acumular segundos
					if(isset($e['SSalida']) && $e['SEntrada']<$e['SSalida']){
						$horasD=($e['SSalida']-$e['SEntrada'])-3600;
					}
					
					echo  "<tr> 
							<td>{$in}
							</td>
							<td>{$out}
							</td>
							<td>".(date("H:i",$horasD))."
							</td>
							
						   </tr>";
				}

				echo "</table>";




				// //tabla con horas
				
				// $arr=$db->horasMes($idUsr);
				
				// //TABLA 1 horas
				// echo "<table class=\"table table-hover table-dark\" name=\"copiame\">
				// 		<tr>
				// 			<th scope=\"col\">fecha entrada
				// 			</th>
				// 			<th scope=\"col\">fecha salida
				// 			</th>
				// 			<th scope=\"col\">horas 
				// 			</th>
							
				// 		</tr>";

				
				// //print_r($arr);

				// //horas
				// $horasD=0;
				// //dias
				// $dias="--";
				

				// $mismoDia="--";

				
				
				// foreach($arr as $e){

				// 	$in=date("d-m-Y H:i",$e['SEntrada']);

				// 	//comprobamos si estamos en el mismo día
				// 	if($mismoDia!==($hoy=explode(" ",$in)[0])){
				// 		$mismoDia=$hoy;
				// 		$horasD=-3600;
						
				// 	}

				
					

				// 	if(!isset($e['SSalida'])){$out="--";}else{$out=date("d-m-Y H:i",$e['SSalida']);


				// 	}
				// 	//acumular segundos
				// 	if(isset($e['SSalida']) && $e['SEntrada']<$e['SSalida']){
				// 		$horasD+=($e['SSalida']-$e['SEntrada']);
				// 	}
					
				// 	echo  "<tr> 
				// 			<td>{$in}
				// 			</td>
				// 			<td>{$out}
				// 			</td>
				// 			<td>".(date("H:i",$horasD))."
				// 			</td>
							
				// 		   </tr>";
				// }

				// echo "</table>";




				//meses
				
				echo "<table name=\"copiame\">
						<tr>
							
							<th>mes
							</th>
							<th>horas totales del mes
							</th>
							
						</tr>";


				$horasMes=-3600;
				$mismoMes="--";
				//echo $iteraciones=count($arr);
				$contador=0;

				function conversorSegundosHoras($tiempo_en_segundos) {
   								 $horas = floor($tiempo_en_segundos / 3600);
   								 $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    							$segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    							return round((1+$horas-($horas/24))) . ':' . $minutos . ":" . $segundos;
							}
				
				foreach($arr as $e){
					
					$in=date("M-Y",$e['SEntrada']);
					if($mismoMes==="--"){$mismoMes=$in;}
					

					//comprobamos si estamos en el mismo día O SI ES LA ÚLTImA ITERACIÓN
					
						if($mismoMes!==$in || $contador==count($arr)-1){
								
							//ESTO ES ÚNICAMENTE PARA LA ÚLTIMA ITERACIÓN						
							if($contador==count($arr)-1){
								if(isset($e['SSalida']) && $e['SEntrada']<$e['SSalida']){
									$horasMes+=($e['SSalida']-$e['SEntrada']);
								}
							}
							//<td>".(date("H:i",$horasMes))."
							
							echo  "<tr> 
								
								<td>{$mismoMes}
								</td>
								
								
								<td>".(conversorSegundosHoras($horasMes))."
								</td>
								
							</tr>";			
							$horasMes=-3600;
							$mismoMes=$in;
							
						}

						//acumular segundos x mes
					
						if(isset($e['SSalida']) && $e['SEntrada']<$e['SSalida']){
							$horasMes+=($e['SSalida']-$e['SEntrada']);
						}

						
					
					
					


					$contador++;

					
					
					
				}
					
					
					echo "</table>";
				
				

					
					echo "<div class=\"tablaContenedor \">";
					?>

						<select name="sel" class="" id="">
								<option value="v1">hora</option>
								<option value="v3" selected="selected">mes</option>
							
						</select>

					<?php
					echo "<table class=\"margen tablas table table-hover table-dark\" id=\"pizarra\"></table>";
					echo "</div>";
					?>	

						<script>
							//cogemos select
							var arr2=document.getElementsByTagName('select')[0];
							//cogemos tablas
							var tablas=document.getElementsByName('copiame');
							//trazamos el contenedor de la tabla seleccionada
							var pizarra = document.getElementById('pizarra');
							//este array guardará cada una de las tablas por seleccionar
							var tablasArray=[];
							
							
							
							for (i=0;i<=tablas.length;i++) {
								

  								if(typeof tablas[0] !== 'undefined'){
  									
  									
  									tablasArray.push(tablas[0].innerHTML);
  									tablas[0].remove();
  								}
							}
						 	
							
							seleccionar();
							//al cambiar el select!!
							function seleccionar(){
								//tendremos que recorrer un bucle for, todo esto en calidad de poder automatizar el proceso si queremos añadir más tablas
								for(i=0;i<=arr2.length;i++){

									if(arr2.selectedIndex==i){
										
										pizarra.innerHTML= tablasArray[i];
									}
									
								}

								// if(arr2.selectedIndex==0){
								// 	pizarra.innerHTML= tablasArray[0];

								// }else if(arr2.selectedIndex==1){
								// 	pizarra.innerHTML=  tablasArray[1];
								// }
							}

							arr2.onchange=function(){
								seleccionar();

							};

						</script>


					<?php

				$buffer=ob_get_clean();
			}



			






			include "plantillas/blanc.php";
		  

		}

		public function login(){
			if(isset($_REQUEST['send'])){
				//user y contraseña del formulario
				$username=recoge('username');
				$contrasena=recoge('contrasena');
				$userId;
				//comprobamos si existen en la base de datos
				$db=Conectate::getInstance();
				$instancias=$db->select("Select * from usuario where usuario='$username' AND contrasena='$contrasena'");
				if(count($instancias)>0){
					print_r($instancias);
					echo "procesando... ";
					echo $instancias[0]['usuario'];
					$userId=$instancias[0]["id"];
				}else{
					$this->errores[]="usuario o contraseña incorrectos";
				}

				if(count($this->errores)>0){
					foreach($this->errores as $e){
						echo '<p class="errores">'.$e."</p>";
					}
				}else{
					session_start();
					$_SESSION['username']=$username;
					$_SESSION['contrasena']=$contrasena;

					if(isset($userId))$_SESSION['idUsr']=$userId;
					$_SESSION['fotoP']=$db->select("select fotoP from usuario where id='$userId'")[0]['fotoP'];
					$_SESSION['nombre']=$db->getNom($userId);
					$_SESSION['apellidos']=$db->getApe($userId);
					
					$_SESSION['lvl']=$db->getLvl($userId);
					//getLvl($userId);
					header("location:index.php?dir=inicio");
				}

			}
			include "plantillas/login.php";
		}

		public function alta(){
			if (($_SESSION['lvl']==2)) {
			if(isset($_REQUEST['send'])){
				
				$db=Conectate::getInstance();				
				$nombre=recoge('nombre');
				$apellidos=recoge('apellidos');
				$username=recoge('username');
				$contrasena=recoge('contrasena');
				$mail=recoge('mail');
				
				
				if(!(cGeneral($nombre,'nombre',$this->errores,"/^[a-z ,.'-]+$/i"))){if(!isset($nombre)||$nombre="")$this->errores['nombre']="el campo nombre es obligatorio";}

				if(!(cGeneral($apellidos,'apellidos',$this->errores,"/^[a-z ,.'-]+$/i"))){if(!isset($apellidos)||$apellidos="")$this->errores['apellidos']="el campo nombre es obligatorio";}
				
			    if(!(cGeneral($username,'username',$this->errores,"/^([a-z]|[A-Z]|[_]){1}([a-z]|[A-Z]|[_]|[0-9]|[$]|[&]){0,12}$/"))){if(!isset($username)||$username="")$this->errores['username']="el campo usuario es obligatorio";}else
			    
			    	if(count($db->select("select usuario from usuario where usuario='$username'"))>0){$this->errores['usrDup']="este usuario actualmente ya existe en el sistema!";}else{


			    if(!(cGeneral($contrasena,"contrasena",$this->errores,"/^([a-z]|[A-Z]|_|\*|\+|-|\\|\/|[0-9]|\$|&){1,15}$/"))) if(!isset($contrasena) || $contrasena=="")$this->errores['contrasena'].=" el Campo contraseña es obligatorio";

			    if(!cMail($mail,'mail',$this->errores))$this->errores['mail']='El email no ha salido en el formato correcto';



				 
				

				}
				if(count($this->errores)>0){
					foreach($this->errores as $e){
						echo "<p class=\"errores\">"."$e"."</p>";
					}
					
				}else{
					
					
					$hora=date("H:i:s");
					$fecha=date("Y-m-d");
					
					if($db->insert("insert into usuario (`usuario`,`nombre`,`apellidos`,`contrasena`,`mail`,`fotoP`,`fechaAlta`,`lvl`) values ('$username','$nombre','$apellidos','$contrasena','$mail','$fotoP','$fecha',1)")>0)
					{

						header("location:index.php?dir=exito&ex=usuario registrado&loc=inicio");


					}else{header("location:index.php?dir=error"); }
					
				}
				
			}
			
				include "plantillas/alta.php";	
			}
			
		}



		public function borrarCuenta(){
			if (($_SESSION['lvl']==2)) {
				if(isset($_REQUEST['send'])){
					if (!isset($_POST['check'])) {
						$this->errores['check']="debes aceptar para eliminar al usuario";
					}
					$db=Conectate::getInstance();				
					$username=recoge('username');
					
				
					if(!(cGeneral($username,'username',$this->errores,"/^([a-z]|[A-Z]|[_]){1}([a-z]|[A-Z]|[_]|[0-9]|[$]|[&]){0,12}$/"))){if(!isset($username)||$username="")$this->errores['username']="el campo usuario es obligatorio";}

					if(count($this->errores)>0){
						foreach($this->errores as $e){
							echo "<p class=\"errores\">"."$e"."</p>";
						}
					}else{

						if($db->borrarCuenta($username)>0)
						{

							header("location:index.php?dir=exito&ex=Usuario eliminado&loc=inicio");


						}else{
							$this->errores['borrado']="el usuario no existe en la bd"; 
							foreach($this->errores as $e){
								echo "<p class=\"errores\">"."$e"."</p>";
							}
						}
						
						
				   }
					
				}else{
					
					
					
				
						

											
				
				}
			
				include "plantillas/borrarCuenta.php";	
			}
			
		}
		}	

 ?>