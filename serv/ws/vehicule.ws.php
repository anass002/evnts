<?php
	require_once('../libs/vehicule.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllVehicules':
				echo json_encode(vehicule::getAll());
				return false;
				break;
			case 'AddNewVehicule':
				if(!isset($postdata->vehicule)){
					echo json_encode(returnResponse(true,"Missing paramater vehicule "));
					return false;
				}

				$vehicule = json_decode($postdata->vehicule);

				$newVehicule = new vehicule();

				foreach ($vehicule as $key => $value) {
					if(isset($newVehicule->$key)){
						$newVehicule->$key = $value;
					}
				}

				echo json_encode($newVehicule->save());
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