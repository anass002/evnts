<?php
	require_once('../libs/sejour.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllSejours':
				echo json_encode(sejour::getAll());
				return false;
				break;
			case 'AddNewSejour':
				if(!isset($postdata->sejour)){
					echo json_encode(returnResponse(true,"Missing paramater sejour "));
					return false;
				}

				$sejour = json_decode($postdata->sejour);

				$newSejour = new sejour();

				foreach ($sejour as $key => $value) {
					if(isset($newSejour->$key)){
						$newSejour->$key = $value;
					}
				}

				echo json_encode($newSejour->save());
				return false;
				break;
			case 'deleteSejour':
				if(!isset($postdata->id)){
					echo json_encode(returnResponse(true,"Missing id paramater"));
					return false;
				}

				echo json_encode(sejour::deleteById($postdata->id));
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