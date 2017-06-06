<?php 
	//require_once('../libs/auth.class.php');
	require_once('../libs/majordome.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllMajordome':
				echo json_encode(majordome::getAll());
				return false;
				break;
			case 'AddNewMajordome':
				if(!isset($postdata->majordome)){
					echo json_encode(returnResponse(true,"Missing paramater majordome "));
					return false;
				}

				$majordome = json_decode($postdata->majordome);

				$newMajordome = new majordome();

				foreach ($majordome as $key => $value) {
					if(isset($newMajordome->$key)){
						$newMajordome->$key = $value;
					}
				}

				echo json_encode($newMajordome->save());
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