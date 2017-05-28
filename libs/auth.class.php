<?php 
	require_once('config.inc.php');
	require_once('error.inc.php');
	require_once('admin.class.php');
	require_once('../vendor/autoload.php');

	use Lcobucci\JWT\Builder;
	use Lcobucci\JWT\Parser;
	use Lcobucci\JWT\Signer\Hmac\Sha256;
	use Lcobucci\JWT\ValidationData;


	class auth {

		function createAuthToken($login = false , $password = false){
			if( $login === false || $password === false){
				return returnResponse(true , "Missing parameter login or password");
			}

			$user = admin::logIn($login , $password);

			if($user['error'] === true || count($user['data']) == 0){
				return returnResponse(true,"Unable to find user with this credentials ");
			}

			$user = $user['data'][0];

			$signer = new Sha256();
			$createdToken = (new Builder())->setIssuer(JWT_Issuer) 
		        					->setAudience(JWT_Audience) 
		        					->setId(JWT_Id, true) 
		        					->setIssuedAt(time())  
		        					->setSubject(JWT_Subject)
		        					->set('user',json_encode($user['data'][0]));
		        					->sign($signer, JWT_Sign)
		        					->getToken();
		    if(!$createdToken){
		    	return returnResponse(true,"Unable to create Token ");
		    }
		    return returnResponse(false,$createdToken);    					
		}

		function vefifySignAuthToken($authToken = false){
			if($authToken === false){
				return returnResponse(true,"Missing authToken");
			}
			if(trim($authToken) == ''){
				return returnResponse(true,"Token Empty redirect to login page");
			}
			$receivedToken = (new Parser())->parse((string) $authToken );
			$signer = new Sha256();
			$data = new ValidationData();
			$data->setIssuer(JWT_Issuer);
			$data->setAudience(JWT_Audience);
			$data->setId(JWT_Id);
			if(!$receivedToken->validate($data) || !$receivedToken->verify($signer,JWT_Sign)){
				return returnResponse(true,"Not authenticated authToken");
			}
			return returnResponse(false,"Autheticated");
		}
	}


?>
