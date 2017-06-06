<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class sejour {
		var $id;
		var $nom;
		var $description;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM nature_sejour_table WHERE id = ". pg_escape_string($id);
			return sejpur::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM nature_sejour_table";
			return sejour::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO nature_sejour_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";

			}else{
				$sql = "UPDATE nature_sejour_table SET "
						."nom='".pg_escape_string($this->nom)."', "
						."description='".pg_escape_string($this->description)."', "
						."prix='".pg_escape_string($this->prix)."', "
						."customdata='".json_encode($this->customdata)."' "
						."WHERE id = ".pg_escape_string($this->id);
			}
		}

		private function readRow($row = false){
			if($row === false){
				return false;
			}
			if(trim($row['id']) !== ''){
				$sejour = new sejour();

				$sejour->id = trim($row['id']);
				$sejour->nom = trim($row['nom']);
				$sejour->description = trim($row['description']);
				$sejour->prix = trim($row['prix']);
				$sejour->customdata = json_decode($row['customdata']);
				return $sejour;				
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
				$obj = sejour::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>	