<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('db.inc.php');

	class profile {
		var $id;
		var $nom;
		var $prenom;
		var $adresse;
		var $email;
		var $tel;
		var $wtel;
		var $sexe;
		var $age;
		var $profession;
		var $customdata;

		function __construct(){
			$this->id = false ;
			$this->nom = '';
			$this->prenom = '';
			$this->adresse = '';
			$this->email = '';
			$this->tel = '';
			$this->wtel = '';
			$this->sexe = '';
			$this->age = '';
			$this->profession = '';
			$this->customdata = new stdClass();
		}

		function save(){
			if(!isset($this)){
				return returnResponse(true,"Object not instancied. Cannot save it !");
			}

			if($this->id === false){
				$sql = "INSERT INTO profile_table VALUES (DEFAULT, "
						."'".pg_escape_string($this->nom)."', "
						."'".pg_escape_string($this->prenom)."', "
						."'".pg_escape_string($this->adresse)."', "
						."'".pg_escape_string($this->email)."', "
						."'".pg_escape_string($this->tel)."', "
						."'".pg_escape_string($this->wtel)."', "
						."'".pg_escape_string($this->sexe)."', "
						."'".pg_escape_string($this->age)."', "
						."'".pg_escape_string($this->profession)."', "
						."'".json_encode($this->customdata)."' "
						.") RETURNING id";
			}else{
				$sql = "UPDATE profile_table SET "
							."nom='".pg_escape_string($this->nom)."', "
							."prenom='".pg_escape_string($this->prenom)."', "
						    ."adresse='".pg_escape_string($this->adresse)."', "
						    ."email='".pg_escape_string($this->email)."', "
						    ."tel='".pg_escape_string($this->tel)."', "
						    ."wtel='".pg_escape_string($this->wtel)."', "
						    ."sexe='".pg_escape_string($this->sexe)."', "
					     	."age='".pg_escape_string($this->age)."', "
					    	."profession='".pg_escape_string($this->profession)."', "
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
				$profile = new profile();

				$profile->id = trim($row['id']);
				$profile->nom = trim($row['nom']);
				$profile->prenom = trim($row['prenom']);
				$profile->adresse = trim($row['adresse']);
				$profile->email = trim($row['email']);
				$profile->tel = trim($row['tel']);
				$profile->wtel = trim($row['wtel']);
				$profile->sexe = trim($row['sexe']);
				$profile->age = trim($row['age']);
				$profile->profession = trim($row['profession']);
				$profile->customdata = json_decode($row['customdata']);

				return $profile;
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
				$obj = profile::readRow($result['data'][$i]);
				if($obj !== false && $obj->id !== false)
					$results[] = $obj;
			}
			return returnResponse(false, $results);
		}
	}
?>
