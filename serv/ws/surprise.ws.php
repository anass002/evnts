<?php 
	//require_once('../libs/auth.class.php');
	require_once('../libs/surprise.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllSurprises':
				echo json_encode(surprise::getAll());
				return false;
				break;
			case 'AddNewSurprise':
				if(!isset($postdata->surprise)){
					echo json_encode(returnResponse(true,"Missing paramater profile "));
					return false;
				}

				$surprise = json_decode($postdata->surprise);

				$newSurprise = new surprise();

				foreach ($surprise as $key => $value) {
					if(isset($newSurprise->$key)){
						$newSurprise->$key = $value;
					}
				}

				echo json_encode($newSurprise->save());
				return false;
				break;
			case 'deleteSurprise':
				if(!isset($postdata->id)){
					echo json_encode(returnResponse(true,"Missing id paramater"));
					return false;
				}

				echo json_encode(surprise::deleteById($postdata->id));
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