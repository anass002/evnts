<?php
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class surprise {
		var $id;
		var $description;
		var $contenu;
		var $prix;
		var $customdata;

		function __construct(){
			$this->id = false;
			$this->description = '';
			$this->contenu = '';
			$this->prix = '';
			$this->customdata = new stdClass();
		}

		function getById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id to complete getById");
			}

			$sql = "SELECT * FROM pack_surprise_table WHERE id = ". pg_escape_string($id);
			return surprise::execRequest($sql);
		}

		function getAll(){
			$sql = "SELECT * FROM pack_surprise_table";
			return surprise::execRequest($sql);
		}

		function deleteById($id = false){
			if($id === false){
				return returnResponse(true,"Missing parameter id ");
			}
			$sql = "DELETE FROM pack_surprise_table WHERE id = ".pg_escape_string($id);
			return surprise::execRequest($sql);
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO pack_surprise_table VALUES (DEFAULT, "
							."'".pg_escape_string($this->description)."', "
							."'".pg_escape_string($this->contenu)."', "
							."'".pg_escape_string($this->prix)."', "
							."'".json_encode($this->customdata)."' "
							.") RETURNING id";
			}else{
				$sql = "UPDATE pack_surprise_table SET "
							."description='".pg_escape_string($this->description)."', "
							."contenu='".pg_escape_string($this->contenu)."', "
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
				$surprise = new surprise();

				$surprise->id = trim($row['id']);
				$surprise->description = trim($row['description']);
				$surprise->contenu = trim($row['contenu']);
				$surprise->prix = trim($row['prix']);
				$surprise->customdata = json_decode($row['customdata']);

				return $surprise;

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
				$obj = surprise::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>	