<?php
	require_once('../libs/region.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllRegions':
				echo json_encode(region::getAll());
				return false;
				break;
			case 'AddNewRegion':
				if(!isset($postdata->region)){
					echo json_encode(returnResponse(true,"Missing paramater profile "));
					return false;
				}

				$region = json_decode($postdata->region);

				$newregion = new region();

				foreach ($region as $key => $value) {
					if(isset($newregion->$key)){
						$newregion->$key = $value;
					}
				}

				echo json_encode($newregion->save());
				return false;
				break;
			case 'deleteRegion':
				if(!isset($postdata->id)){
					echo json_encode(returnResponse(true,"Missing id paramater"));
					return false;
				}

				echo json_encode(region::deleteById($postdata->id));
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