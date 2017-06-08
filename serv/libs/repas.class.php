<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class repas {
		var $id;
		var $nom;
		var $emplacement;
		var $ensalle;
		var $hotel;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = '';
			$this->emplacement = '';
			$this->hotel = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM repas_table WHERE id = ". pg_escape_string($id);
			return repas::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM repas_table";
			return repas::execRequest($sql);
		}

		function deleteById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id ");
			}
			$sql = "DELETE FROM repas_table WHERE id = ".pg_escape_string($id);
			return repas::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO repas_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->emplacement)."', "
						."'".pg_escape_string($this->hotel)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE repas_table SET "
							."nom='".pg_escape_string($this->nom)."', "
							."description='".pg_escape_string($this->description)."', "
							."emplacement='".pg_escape_string($this->emplacement)."', "
							."hotel='".pg_escape_string($this->hotel)."', "
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
				$repas = new repas();

				$repas->id = trim($row['id']);
				$repas->nom = trim($row['nom']);
				$repas->description = trim($row['description']);
				$repas->emplacement = trim($row['emplacement']);
				$repas->hotel = trim($row['hotel']);
				$repas->prix = trim($row['prix']);
				$repas->customdata = json_decode($row['customdata']);
				return $repas;

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
				$obj = repas::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>	