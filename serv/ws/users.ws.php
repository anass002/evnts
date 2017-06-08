<?php 
	//require_once('../libs/auth.class.php');
	require_once('../libs/profile.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllProfiles':
				echo json_encode(profile::getAll());
				return false;
				break;
			case 'AddNewProfile':
				if(!isset($postdata->profile)){
					echo json_encode(returnResponse(true,"Missing paramater profile "));
					return false;
				}

				$profile = json_decode($postdata->profile);

				$newProfile = new profile();

				foreach ($profile as $key => $value) {
					if(isset($newProfile->$key)){
						$newProfile->$key = $value;
					}
				}

				echo json_encode($newProfile->save());
				return false;
				break;
			case 'deleteProfile':
				if(!isset($postdata->id)){
					echo json_encode(returnResponse(true,"Missing id paramater"));
					return false;
				}

				echo json_encode(profile::deleteById($postdata->id));
				return false;
				break;				
			default:
				echo json_encode(returnResponse(true,"No Action Provided !"));
				return false;
				break;
		}
	}else{
		return false;
	}


?>