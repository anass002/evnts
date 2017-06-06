<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');


	class admin {
		var $id;
		var $nom;
		var $login;
		var $password;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->login = '';
			$this->password = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM admin_table WHERE id = ". pg_escape_string($id);
			return admin::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM admin_table";
			return admin::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO admin_table VALUES (DEFAULT, "
							."'".pg_escape_string($this->nom)."', "
							."'".pg_escape_string($this->login)."', "
							."'".pg_escape_string($this->password)."', "
							."'".pg_escape_string(json_encode($this->customdata))."' "
							.") RETURNING id";
			}else{
				$sql = "UPDATE admin_table SET "
						."nom='".pg_escape_string($this->nom)."', "
						."login='".pg_escape_string($this->login)."', "
						."password='".pg_escape_string($this->password)."', "
						."customdata=='".json_encode($this->customdata)."' "
						."WHERE id = ".pg_escape_string($this->id);
			}

			$result = dbExecRequest($sql);
			if($result['error'] === true){
				return returnResponse(true,"Unable to execute ".$sql);
			}

			return returnResponse(false,$result['data']);
		}

		private function readRow($row = false){
			if($row === false){
				return false;
			}
			if(trim($row['id']) !== ''){
				$admin = new admin();

				$admin->id = trim($row['id']);
				$admin->nom = trim($row['nom']);
				$admin->login = trim($row['login']);
				$admin->password = trim($row['password']);
				$admin->customdata = json_decode($row['customdata']);

				return $admin;
			}
			return false;
		}

		private function execRequest($sql = false){
			if($sql === false){
				return returnResponse(true,"Missing sql parameter");
			}
			
			$result = dbExecRequest($sql);
			if($result['error'] === true){
				return returnResponse(true,$result['data']);
			}
			$cpt = count($result['data']);
			$results = Array();
			for($i=0 ; $i<$cpt ; $i++){
				$obj = admin::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>