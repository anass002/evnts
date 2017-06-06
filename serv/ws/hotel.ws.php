<?php 
	//require_once('../libs/auth.class.php');
	require_once('../libs/hotel.class.php');

	$postdata = file_get_contents("php://input");
	$postdata = json_decode($postdata);

	if(isset($postdata->action)){
		switch ($postdata->action) {
			case 'getAllHotels':
				echo json_encode(hotel::getAll());
				return false;
				break;
			case 'AddNewHotel':
				if(!isset($postdata->hotel)){
					echo json_encode(returnResponse(true,"Missing paramater profile "));
					return false;
				}

				$hotel = json_decode($postdata->hotel);
				$newHotel = new hotel();
				
				foreach ($hotel as $key => $value) {
					if(isset($newHotel->$key)){
						$newHotel->$key = $value;
					}
				}

				echo json_encode($newHotel->save());
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