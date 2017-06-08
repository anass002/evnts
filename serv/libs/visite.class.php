<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class visite {
		var $id;
		var $nom;
		var $description;
		var $destination;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = '';
			$this->destination = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM visite_table WHERE id = ". pg_escape_string($id);
			return visite::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM visite_table";
			return visite::execRequest($sql);
		}

		function deleteById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id ");
			}
			$sql = "DELETE FROM visite_table WHERE id = ".pg_escape_string($id);
			return visite::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO visite_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->destination)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE visite_table SET "
						."nom='".pg_escape_string($this->nom)."', "
						."description='".pg_escape_string($this->description)."', "
						."destination='".pg_escape_string($this->destination)."', "
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
				$visite = new visite();

				$visite->id = trim($row['id']);
				$visite->nom = trim($row['nom']);
				$visite->description = trim($row['description']);
				$visite->destination = trim($row['destination']);
				$visite->prix = trim($row['prix']);
				$visite->customdata = json_decode(trim($row['customdata']));
				

				return $visite;

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
				$obj = visite::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>
