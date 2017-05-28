<?php
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class hotel {
		var $id;
		var $nom;
		var $description;
		var $ville;
		var $repas;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->nom = '';
			$this->description = '';
			$this->ville = '';
			$this->repas = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM hotel_table WHERE id = ". pg_escape_string($id);
			return hotel::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM hotel_table";
			return hotel::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO hotel_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->description)."', "
						."'".pg_escape_string($this->ville)."', "
						."'".pg_escape_string($this->repas)."', "
						."'".pg_escape_string($this->prix)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE hotel_table SET "
							."nom='".pg_escape_string($this->nom)."', "
							."description='".pg_escape_string($this->description)."', "
							."ville='".pg_escape_string($this->ville)."', "
							."repas='".pg_escape_string($this->repas)."', "
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
				$hotel = new hotel();

				$hotel->id = trim(row['id']);
				$hotel->nom = trim(row['nom']);
				$hotel->description = trim(row['description']);
				$hotel->ville = trim(row['ville']);
				$hotel->repas = trim(row['repas']);
				$hotel->prix = trim(row['prix']);
				$hotel->customdata = json_decode($row['customdata']);
				return $hotel;
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
				$obj = hotel::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>