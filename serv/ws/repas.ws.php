<?php 
	//require_once('../libs/auth.class.php');
	require_once('../libs/repas.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllRepas':
				echo json_encode(repas::getAll());
				return false;
				break;
			case 'AddNewRepas':
				if(!isset($postdata->repas)){
					echo json_encode(returnResponse(true,"Missing paramater repas "));
					return false;
				}

				$repas = json_decode($postdata->repas);

				$newRepas = new repas();

				foreach ($repas as $key => $value) {
					if(isset($newRepas->$key)){
						$newRepas->$key = $value;
					}
				}

				echo json_encode($newRepas->save());
				return false;
				break;
			case 'deleteRepas':
				if(!isset($postdata->id)){
					echo json_encode(returnResponse(true,"Missing id paramater"));
					return false;
				}

				echo json_encode(repas::deleteById($postdata->id));
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