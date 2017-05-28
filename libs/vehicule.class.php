<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class vehicule {
		var $id;
		var $nom;
		var $description;
		var $adaptation;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = '';
			$this->adaptation = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM vehicule_table WHERE id = ". pg_escape_string($id);
			return vehicule::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM vehicule_table";
			return vehicule::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO vehicule_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->adaptation)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".pg_escape_string($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE vehicule_table SET "
							."nom='".pg_escape_string($this->nom)."', "
							."description='".pg_escape_string($this->description)."', "
							."adaptation='".pg_escape_string($this->adaptation)."', "
							."prix='".pg_escape_string($this->prix)."', "
							."customdata='".pg_escape_string($this->customdata)."' "
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
				$vehicule = new vehicule();

				$vehicule->id = trim($row['id']);
				$vehicule->nom = trim($row['nom']);
				$vehicule->description = trim($row['description']);
				$vehicule->adaptation = trim($row['adaptation']);
				$vehicule->prix = trim($row['prix']) ;
				$vehicule->customdata = json_decode($row['customdata']);
				
				
				
				

				return $vehicule;
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
				$obj = vehicule::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}	


	}
?>