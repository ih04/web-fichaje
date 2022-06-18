<?php 
	//aquí emplearemos el modelo syngletón para tener una conexión más segura y controlada, así mismo será aquí donde llevaremos a cabo todas las consultas mediante medotos, al tener lo en este archivo lo controlaremos con más facilidad
	
	//aquí guardaré los datos de la conexión
	include_once ("Config.php");
	
	class Conectate extends PDO{

		private static $instance=null;

		public function __construct(){
			parent::__construct("mysql:host=".Config::$dbhost.";"."dbname=".Config::$dbdata,Config::$dbuser,Config::$dbpass);
			parent::exec("set names utf8");
			parent::setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}

		public static function GetInstance(){
			if(self::$instance==null){
				self::$instance = new self();
			}

			return self::$instance;
		}

		//OPERACIONES PARA  LOS NIVELES > 0 USUARIOS

		public function select($sql){
			return self::query($sql)->fetchAll(PDO::FETCH_ASSOC);
			
		}

		public function insert($sql){
			return self::exec($sql);
		}

		//SENTENCIAS PREPARE 

		//devuelve la última id del usuario x de la tabla ficha
		public function idMax($id){
			$r=self::prepare("select max(id) from ficha where id_user=$id;");

			$r->execute();

			return $r->fetchAll()[0][0];

		}

		//insertar entrada en ficha, donde $id = id_user
		public function insertarEntrada($id,$t,$d){
			$r=self::prepare("insert into ficha (`id_user`,`SEntrada`,`HEntrada`) values (?,?,?)");     
			 return $r->execute(array($id,$t,$d));
		}

		//ÚLTIMA SALIDA Y ENTRADA DEL USUARIO
		public function selecSalida($id){
			$id=self::idMax($id);
			//echo $id;

			$r=self::prepare("select SSalida from ficha where id=?");     
			 if($r->execute(array($id))){
			 	return $r->fetchAll();
			 }else{
			 	$errores["selecSalida"]="no se ha llegado a realizar el selec de la salida";
			 }

			 
		}

		public function selecEntrada($id){
				$id=self::idMax($id);
				
				
				$sql="select SEntrada from ficha where id=$id";
				$r=self::prepare($sql);     
			 	$r->execute();

				// print_r(self::query("select SEntrada from ficha where id=17")->fetchAll());
				//print_r($r->fetchAll()[0]);
			 	return $r->fetchAll();

			 
		}

		public function modSalida($id,$t,$d){
			$id=self::idMax($id);

			$r=self::prepare("update ficha set SSalida=$t,HSalida=\"$d\"  where id=$id");     
			return $r->execute();


		}

		public function getFichaId($id){
			$r=prepare("select id from ficha where id_user=$id and id=(select max(id) from ficha");
		}


		public function horasMes($id){
			try {
				$r=self::prepare("select SEntrada,SSalida from ficha where id_user=?");
				$r->execute(array($id));
				return $r->fetchAll();
			
			} catch (PDOException $e) {
				// En este caso guardamos los errores en un archivo de errores log
				// echo $e->getMessage() . $e->getFile() . 
				// $e->getLine() . $e->getCode() . microtime() ;
				// guardamos en ·errores el error que queremos mostrar a los usuarios
				$errores['datos'] = "Ha habido un error <br>";
			}



		}

		public function getLvl($id){
			$r=self::prepare("select lvl from usuario where id=?");
			$r->execute(array($id));
			return  $r->fetchAll()[0][0];
			

		}

		public function getNom($id){
			$r=self::prepare("select nombre from usuario where id=?");
			$r->execute(array($id));
			return  $r->fetchAll()[0][0];
			

		}

		public function getApe($id){
			$r=self::prepare("select apellidos from usuario where id=?");
			$r->execute(array($id));
			return  $r->fetchAll()[0][0];
			

		}

		//si superamos las 22 horas despues de haber fichado entrada ya no se puede fichar salida
		public function superaHora($id){
			
			if((time()-self::selecEntrada($id)[0][0])>79200)return false;
			return true;
		}

		//OPERACIONES NIVEL > 1 ADMINS

		public function borrarCuenta($user){
			
			try {
				return  self::exec("delete from usuario where usuario='".$user."'");
			} catch (PDOException $e) {
				
				return 0;
			}
			
		}

	} 

	
	

 ?>