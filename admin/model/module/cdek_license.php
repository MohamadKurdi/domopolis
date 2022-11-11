<?php
class ModelModuleCdekLicense extends Model {

	public function chechUser($user, $pass)
	{
		$licenseServers = array();

		$licenseServers[] = 'http://cdek.opencart.ru/service/license/';
		$licenseServers[] = 'http://cdek-souz.ru/udata/users/userCheck/';

		$status = 'fail';

		foreach ($licenseServers as $licenseServer) {
			$response = $this->getCurl($licenseServer.'?m='.$user.'&p='.$pass.'');
			$status = $response['content'];
			
			if($status != 'fail')
			{
				return $status;
			}
		}

		return $status;
	}

	public function chechInstalled($module_name, $module_route)
	{
		$status = array('status'=>true, 'redirectlink'=>'');
		$moduletype = explode('/', $module_route);
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled($moduletype[1]);
		if (!in_array($module_name, $extensions)) 
		{
			$this->session->data['error'] = 'Необхожимо установить компонент СДЭК'; 
			$redirectlink = $this->url->link($module_route, 'token=' . $this->session->data['token'], 'SSL');
			$redirectlink = str_replace('&amp;', '&',$redirectlink);
			$status = array('status'=>false, 'redirectlink'=>$redirectlink);
		}

		return $status;
	}

	public function chechLicense()
	{
		$this->load->model('setting/setting');
		$license_status = 'fail';
		$user = 'test';
		$password = 'test';
		
		$settings = $this->model_setting_setting->getSetting('cdekLicense');
		if(isset($settings['cdekLicense_user']) && isset($settings['cdekLicense_password']))
		{
			$user = $settings['cdekLicense_user'];
			$password = $settings['cdekLicense_password'];
			$license_status = $this->chechUser($user, $password);
			if($license_status == 'fail')
			{
				$license = array('status'=>false, 'message'=>'Пользователь '.$user.' не активен! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a>');
			}
			else
			{
				$license = array('status'=>true, 'message'=>'Лицензия '.$user.' работает нормально!');	
			}
		}
		else
		{
			$license = array('status'=>false, 'message'=>'Нет лицензии! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a>');
		}	

		return $license;
	}

	public function getCurl( $url )
	{
	    $options = array(
	        CURLOPT_RETURNTRANSFER => true,     // return web page
	        CURLOPT_HEADER         => false,    // don't return headers
	        CURLOPT_FOLLOWLOCATION => false,     // follow redirects, or false
	        CURLOPT_ENCODING       => "",       // handle all encodings
	        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17", // who am i
	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	        CURLOPT_TIMEOUT        => 120,      // timeout on response
	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	        );
	    $ch      = curl_init( $url );
	    curl_setopt_array( $ch, $options );
	    $content = curl_exec( $ch );
	    $err     = curl_errno( $ch );
	    $errmsg  = curl_error( $ch );
	    $header  = curl_getinfo( $ch );
	    curl_close( $ch );
	    $header['errno']   = $err;
	    $header['errmsg']  = $errmsg;
	    $header['content'] = $content;
	    return $header;
	}
}
?>