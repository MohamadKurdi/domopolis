<?php
	
	
	class ModelKpReCaptcha extends Model {
		private $apiURI = "https://www.google.com/recaptcha/api/siteverify";
		
		public function validate($token){
			$uri = $this->apiURI . "?secret=". $this->config->get('config_google_recaptcha_contact_secret') ."&response=" . $token;
			
			$response = file_get_contents($uri);
			$return = json_decode($response, true);
			
			if($return['success'] == true && $return['score'] > 0.5){
				return true;
			}
			
			return false;
		}
		
	}
	
