<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class majordome {
		var $id;
		var $nom;
		var $description;
		var $disponibilite;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = ''; 
			$this->disponibilite = '';
			$this->prix = '';
			$this->customdata = new stdClass(); 
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM majordome_table WHERE id = ". pg_escape_string($id);
			return majordome::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM majordome_table";
			return majordome::execRequest($sql);
		}

		function deleteById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id ");
			}
			$sql = "DELETE FROM majordome_table WHERE id = ".pg_escape_string($id);
			return majordome::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO majordome_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->disponibilite)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE majordome_table SET "
						."nom='".pg_escape_string($this->nom)."', "
						."description='".pg_escape_string($this->description)."', "
						."disponibilité='".pg_escape_string($this->disponibilite)."', "
						."prix='".pg_escape_string($this->prix)."', "
						."customdata='".json_encode($this->customdata)."' "
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
				$majordome = new majordome();

				$majordome->id = trim($row['id']);
				$majordome->nom = trim($row['nom']);
				$majordome->description = trim($row['description']); 
				$majordome->disponibilite = trim($row['disponibilité']);
				$majordome->prix = trim($row['prix']);
				$majordome->customdata = json_decode($row['customdata']); 
				return $majordome;
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
				$obj = majordome::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>