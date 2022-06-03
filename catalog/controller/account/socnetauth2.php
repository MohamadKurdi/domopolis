<?php
class ControllerAccountsocnetauth2 extends Controller {
	private $error = array();

	public function frame()
	{
		$this->language->load('account/socnetauth2');
		$this->load->model('account/socnetauth2');
		
		$socnetauth_data = unserialize( $this->session->data['socnetauth2_confirmdata'] );
		$socnetauth_data2 = $socnetauth_data;
		
		foreach($socnetauth_data2 as $key=>$val)
		{
			if( is_array( $val ) ) unset($socnetauth_data2[$key]);
		}
		
		
		if( strstr( implode(',', $socnetauth_data2), '1,2,3,4') )
		{
			$this->confirmform(array("email" => $socnetauth_data[4],
									 "identity" => $socnetauth_data[5],
									 "link" => $socnetauth_data[6],
									 "provider" => $socnetauth_data[7],
									 "data" => serialize($socnetauth_data[8]) ) );
			return;
		}
		
		if( ($this->request->server['REQUEST_METHOD'] == 'POST') && 
			$data = $this->validate($socnetauth_data['data'])  ) 
		{
			
			if( !empty($data['email']) 
				&& $this->config->get('socnetauth2_email_auth') == 'confirm' 
				&& !$this->model_account_socnetauth2->checkUniqEmail( $data['email'] ) 
			)
			{	
				$this->session->data['controlled_email'] = $data['email'];
				
				$this->model_account_socnetauth2->sendConfirmEmail( $data );
				$this->confirmform($data);
				return;
			}
		
			$this->session->data['socnetauth2_confirmdata'] = '';
			$customer_id = $this->model_account_socnetauth2->addCustomer( $data );
			
			$this->session->data['customer_id'] = $customer_id;	
			$this->redirect( $this->url->link('account/socnetauth2/success', '', 'SSL') );
		}
		
		$this->data['action'] = $this->url->link('account/socnetauth2/frame', '', 'SSL');
		
		$this->data['header'] = $this->language->get('header');
		$this->data['header_notice'] = $this->language->get('header_notice');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['text_none'] = $this->language->get('text_none');
		
		/* kin insert metka: c1 */
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->data['firstname_required'] = $this->config->get('socnetauth2_confirm_firstname_required');
		$this->data['lastname_required']  = $this->config->get('socnetauth2_confirm_lastname_required');
		$this->data['email_required']     = $this->config->get('socnetauth2_confirm_email_required');
		$this->data['telephone_required'] = $this->config->get('socnetauth2_confirm_telephone_required');
		$this->data['company_required']   = $this->config->get('socnetauth2_confirm_company_required');
		$this->data['postcode_required']  = $this->config->get('socnetauth2_confirm_postcode_required');
		$this->data['country_required']   = $this->config->get('socnetauth2_confirm_country_required');
		$this->data['zone_required']      =	$this->config->get('socnetauth2_confirm_zone_required');
		$this->data['city_required']      = $this->config->get('socnetauth2_confirm_city_required');
		$this->data['address_1_required'] = $this->config->get('socnetauth2_confirm_address_1_required');
		
		$old_dobor = array();
		
		if( $this->config->get('socnetauth2_dobortype') == 'every' )
		{
			$old_dobor = $this->model_account_socnetauth2->getOldDoborData( $socnetauth_data );
		}
		
		
		$this->load->model('localisation/country');
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

		if( isset($this->request->post['company']) )
		{
			$this->data['company'] = $this->request->post['company'];
		}
		elseif( !empty( $old_dobor['company'] ) )
		{
			$this->data['company'] = $old_dobor['company'];
		}
		else
		{
			$this->data['company'] = '';
		}
		
		if( isset($socnetauth_data['company']) )
		{
			$this->data['is_company'] = 1;
		}
		else
		{
			$this->data['is_company'] = 0;
		}
		
		if( isset($this->request->post['address_1']) )
		{
			$this->data['address_1'] = $this->request->post['address_1'];
		}
		elseif( !empty( $old_dobor['address_1'] ) )
		{
			$this->data['address_1'] = $old_dobor['address_1'];
		}
		else
		{
			$this->data['address_1'] = '';
		}
		
		if( isset($socnetauth_data['address_1']) )
		{
			$this->data['is_address_1'] = 1;
		}
		else
		{
			$this->data['is_address_1'] = 0;
		}
		
		if( isset($this->request->post['postcode']) )
		{
			$this->data['postcode'] = $this->request->post['postcode'];
		}
		elseif( !empty( $old_dobor['postcode'] ) )
		{
			$this->data['postcode'] = $old_dobor['postcode'];
		}
		else
		{
			$this->data['postcode'] = '';
		}
		
		if( isset($socnetauth_data['postcode']) )
		{
			$this->data['is_postcode'] = 1;
		}
		else
		{
			$this->data['is_postcode'] = 0;
		}
		
		if( isset($this->request->post['city']) )
		{
			$this->data['city'] = $this->request->post['city'];
		}
		elseif( !empty( $old_dobor['city'] ) )
		{
			$this->data['city'] = $old_dobor['city'];
		}
		else
		{
			$this->data['city'] = '';
		}
		
		if( isset($socnetauth_data['city']) )
		{
			$this->data['is_city'] = 1;
		}
		else
		{
			$this->data['is_city'] = 0;
		}
		
		if( isset($this->request->post['zone']) )
		{
			$this->data['zone'] = $this->request->post['zone'];
		}
		elseif( !empty( $old_dobor['zone'] ) )
		{
			$this->data['zone'] = $old_dobor['zone'];
		}
		else
		{
			$this->data['zone'] = '';
		}
		
		if( isset($socnetauth_data['zone']) )
		{
			$this->data['is_zone'] = 1;
		}
		else
		{
			$this->data['is_zone'] = 0;
		}
		
		
		if( isset($this->request->post['country']) )
		{
			$this->data['country'] = $this->request->post['country'];
		}
		elseif( !empty( $old_dobor['country'] ) )
		{
			$this->data['country'] = $old_dobor['country'];
		}
		else
		{
			$this->data['country'] = $this->config->get('config_country_id');
		}
		
		if( isset($socnetauth_data['country']) )
		{
			$this->data['is_country'] = 1;
		}
		else
		{
			$this->data['is_country'] = 0;
		}
		
		if( !empty( $this->error['company'] ) )
		{
			$this->data['error_company'] = $this->error['company'];
		}
		else
		{
			$this->data['error_company'] = '';
		}
		
		if( !empty( $this->error['address_1'] ) )
		{
			$this->data['error_address_1'] = $this->error['address_1'];
		}
		else
		{
			$this->data['error_address_1'] = '';
		}
		
		if( !empty( $this->error['postcode'] ) )
		{
			$this->data['error_postcode'] = $this->error['postcode'];
		}
		else
		{
			$this->data['error_postcode'] = '';
		}
		
		if( !empty( $this->error['city'] ) )
		{
			$this->data['error_city'] = $this->error['city'];
		}
		else
		{
			$this->data['error_city'] = '';
		}
		
		if( !empty( $this->error['zone'] ) )
		{
			$this->data['error_zone'] = $this->error['zone'];
		}
		else
		{
			$this->data['error_zone'] = '';
		}
		
		if( !empty( $this->error['country'] ) )
		{
			$this->data['error_country'] = $this->error['country'];
		}
		else
		{
			$this->data['error_country'] = '';
		}
		
		/* 
		company
		address_1
		address_2
		postcode
		city
		zone_id
		country_id
		end kin metka: c1 */
		
		$this->data['text_submit'] = $this->language->get('text_submit');
		
		
		
		if( !empty($this->request->post['firstname']) )
		{
			$this->data['firstname'] = $this->request->post['firstname'];
		}
		elseif( !empty($socnetauth_data['firstname']) )
		{
			$this->data['firstname'] = $socnetauth_data['firstname'];
		}
		elseif( !empty( $old_dobor['firstname'] ) )
		{
			$this->data['firstname'] = $old_dobor['firstname'];
		}
		else
		{
			$this->data['firstname'] = '';
		}
		
		if( isset($socnetauth_data['firstname']) )
		{
			$this->data['is_firstname'] = 1;
		}
		else
		{
			$this->data['is_firstname'] = 0;
		}
		
		if( !empty($this->request->post['lastname']) )
		{
			$this->data['lastname'] = $this->request->post['lastname'];
		}
		elseif( !empty($socnetauth_data['lastname']) )
		{
			$this->data['lastname'] = $socnetauth_data['lastname'];
		}
		elseif( !empty( $old_dobor['lastname'] ) )
		{
			$this->data['lastname'] = $old_dobor['lastname'];
		}
		else
		{
			$this->data['lastname'] = '';
		}
		
		if( isset($socnetauth_data['lastname']) )
		{
			$this->data['is_lastname'] = 1;
		}
		else
		{
			$this->data['is_lastname'] = 0;
		}
		
		if( !empty($this->request->post['email']) )
		{
			$this->data['email'] = $this->request->post['email'];
		}
		elseif( !empty($socnetauth_data['email']) )
		{
			$this->data['email'] = $socnetauth_data['email'];
		}
		elseif( !empty( $old_dobor['email'] ) )
		{
			$this->data['email'] = $old_dobor['email'];
		}
		else
		{
			$this->data['email'] = '';
		}
		
		if( isset($socnetauth_data['email']) )
		{
			$this->data['is_email'] = 1;
		}
		else
		{
			$this->data['is_email'] = 0;
		}
		
		if( !empty($this->request->post['telephone']) )
		{
			$this->data['telephone'] = $this->request->post['telephone'];
		}
		elseif( !empty($socnetauth_data['telephone']) )
		{
			$this->data['telephone'] = $socnetauth_data['telephone'];
		}
		elseif( !empty( $old_dobor['telephone'] ) )
		{
			$this->data['telephone'] = $old_dobor['telephone'];
		}
		else
		{
			$this->data['telephone'] = '';
		}
		
		if( isset($socnetauth_data['telephone']) )
		{
			$this->data['is_telephone'] = 1;
		}
		else
		{
			$this->data['is_telephone'] = 0;
		}
		
		if( !empty( $this->error['firstname'] ) )
		{
			$this->data['error_firstname'] = $this->error['firstname'];
		}
		else
		{
			$this->data['error_firstname'] = '';
		}
		
		if( !empty( $this->error['lastname'] ) )
		{
			$this->data['error_lastname'] = $this->error['lastname'];
		}
		else
		{
			$this->data['error_lastname'] = '';
		}
		
		if( !empty( $this->error['email'] ) )
		{
			$this->data['error_email'] = $this->error['email'];
		}
		else
		{
			$this->data['error_email'] = '';
		}
		
		if( !empty( $this->error['telephone'] ) )
		{
			$this->data['error_telephone'] = $this->error['telephone'];
		}
		else
		{
			$this->data['error_telephone'] = '';
		}
		
		//-------------------------
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/socnetauth2_frame.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/socnetauth2_frame.tpl';
		} else {
			$this->template = 'default/template/account/socnetauth2_frame.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function success()
	{
		$this->language->load('account/socnetauth2');
		$this->data['header'] = $this->language->get('header');
		$this->data['success'] = $this->language->get('success');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/socnetauth2_frame_success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/socnetauth2_frame_success.tpl';
		} else {
			$this->template = 'default/template/account/socnetauth2_frame_success.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	
	public function confirmform($data = array() )
	{
		$this->language->load('account/socnetauth2');
		$this->data['confirmform_header'] = $this->language->get('confirmform_header');
		$this->data['confirmform_entry_code'] = $this->language->get('confirmform_entry_code');
		$this->data['confirmform_message'] = $this->language->get('confirmform_message');
		$this->data['confirmform_button'] = $this->language->get('confirmform_button');
		
		
		$this->data['action'] = $this->url->link('account/socnetauth2/confirmation', '', 'SSL');
		
		$this->data['error_code'] = '';
		
		if( !empty( $this->error['error_code'] ) )
		$this->data['error_code'] = $this->error['error_code'];
		
		if( !empty($this->request->post['data']) )
		{
			$this->data['data'] = $this->request->post['data'];
		}
		else
		{
			$this->data['data'] = $data;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/socnetauth2_frame_confirmform.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/socnetauth2_frame_confirmform.tpl';
		} else {
			$this->template = 'default/template/account/socnetauth2_frame_confirmform.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
	
	public function confirmation()
	{
		$this->language->load('account/socnetauth2');
		$this->load->model('account/socnetauth2');
		
		if( $this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateConfirm() )
		{
			$this->session->data['socnetauth2_confirmdata'] = '';
			$this->request->post['data']['email'] = $this->session->data['controlled_email'];
			
			
			$customer_id = $this->model_account_socnetauth2->addCustomerAfterConfirm( $this->request->post['data'] );
			
			$this->session->data['customer_id'] = $customer_id;	
			
			$this->redirect( $this->url->link('account/socnetauth2/success', '', 'SSL') );
		}
		
		$this->confirmform();
	}
	
  	private function validateConfirm() 
	{
    	if( empty($this->request->post['code']) )
		{
			$this->error['error_code'] = $this->language->get('error_code_empty');
		}
		elseif( !$this->model_account_socnetauth2->checkConfirmCode( 
					$this->request->post['data']['identity'], 
					$this->request->post['code'] ) )
		{
			$this->error['error_code'] = $this->language->get('error_code_invalid');
		}
		elseif( empty($this->session->data['controlled_email']) )
		{
			exit('error_email');
		}
		
		
		if( $this->error ) return false;
		else return true;
	}
	
  	private function validate($data) {
    	
		if( isset( $this->request->post['firstname'] ) && 
			empty( $this->request->post['firstname'] ) &&
			$this->config->get('socnetauth2_confirm_firstname_required') 
		)
		{
			$this->error['firstname'] = $this->language->get('error_firstname');
		}
		
		if( isset( $this->request->post['lastname'] ) && 
			empty( $this->request->post['lastname'] ) &&
			$this->config->get('socnetauth2_confirm_lastname_required')  
		)
		{
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		
		if( isset( $this->request->post['email'] ) && 
			empty( $this->request->post['email'] ) &&
			$this->config->get('socnetauth2_confirm_email_required') 
		)
		{
			$this->error['email'] = $this->language->get('error_email');
		}
		elseif( 
			!empty( $this->request->post['email'] ) && 
			!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'] ) &&
			$this->config->get('socnetauth2_confirm_firstname_required') 
		)
		{
			$this->error['email'] = $this->language->get('error_email2');
		}
		
		if( isset( $this->request->post['telephone'] ) && 
			empty( $this->request->post['telephone'] ) &&
			$this->config->get('socnetauth2_confirm_telephone_required') )
		{
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		
		if( isset( $this->request->post['company'] ) && 
			empty( $this->request->post['company'] ) &&
			$this->config->get('socnetauth2_confirm_company_required') )
		{
			$this->error['company'] = $this->language->get('error_company');
		}
		
		if( isset( $this->request->post['address_1'] ) && 
			empty( $this->request->post['address_1'] ) &&
			$this->config->get('socnetauth2_confirm_address_1_required') )
		{
			$this->error['address_1'] = $this->language->get('error_address_1');
		}
		
		if( isset( $this->request->post['postcode'] ) && 
			empty( $this->request->post['postcode'] ) &&
			$this->config->get('socnetauth2_confirm_postcode_required') )
		{
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
		if( isset( $this->request->post['city'] ) && 
			empty( $this->request->post['city'] ) &&
			$this->config->get('socnetauth2_confirm_city_required') )
		{
			$this->error['city'] = $this->language->get('error_city');
		}
		
		if( isset( $this->request->post['zone'] ) && 
			empty( $this->request->post['zone'] ) &&
			$this->config->get('socnetauth2_confirm_zone_required') )
		{
			$this->error['zone'] = $this->language->get('error_zone');
		}
		
		if( isset( $this->request->post['country'] ) && 
			empty( $this->request->post['country'] ) &&
			$this->config->get('socnetauth2_confirm_country_required') )
		{
			$this->error['country'] = $this->language->get('error_country');
		}
		
    	if (!$this->error) {
			if( !empty($this->request->post['firstname']) )
			{
				$data['firstname'] = $this->request->post['firstname'];
			}
			
			if( !empty($this->request->post['lastname']) )
			{
				$data['lastname']  = $this->request->post['lastname'];
			}
						
			if( !empty($this->request->post['email']) )
			{
				$data['email']  = $this->request->post['email'];
			}
						
			if( !empty($this->request->post['telephone']) )
			{
				$data['telephone']  = $this->request->post['telephone'];
			}
			
			if( !empty($this->request->post['company']) )
			{
				$data['company']  = $this->request->post['company'];
			}
			
			if( !empty($this->request->post['address_1']) )
			{
				$data['address_1']  = $this->request->post['address_1'];
			}
			
			if( !empty($this->request->post['postcode']) )
			{
				$data['postcode']  = $this->request->post['postcode'];
			}
			
			if( !empty($this->request->post['city']) )
			{
				$data['city']  = $this->request->post['city'];
			}
			
			if( !empty($this->request->post['zone']) )
			{
				$data['zone']  = $this->request->post['zone'];
			}
			
			if( !empty($this->request->post['country']) )
			{
				$data['country']  = $this->request->post['country'];
			}
			
			
      		return $data;
    	} else {
      		return false;
    	}  	
  	}
	
	
	protected function isNeedConfirm($data)
	{
		$confirm_data = array();
		
		if( $this->config->get('socnetauth2_confirm_firstname_status') == 2 || (
			$this->config->get('socnetauth2_confirm_firstname_status') == 1 && empty($data['firstname'])
			) )
		{
			$confirm_data['firstname'] = $data['firstname'];
		}
		
		if( $this->config->get('socnetauth2_confirm_lastname_status') == 2 || (
			$this->config->get('socnetauth2_confirm_lastname_status') == 1 && empty($data['lastname'])
		) )
		{
			$confirm_data['lastname'] = $data['lastname'];
		}
		
		if( $this->config->get('socnetauth2_confirm_email_status') == 2 || (
			$this->config->get('socnetauth2_confirm_email_status') == 1 && empty($data['email'])
			) )
		{
			$confirm_data['email'] = $data['email'];
		}
		
		if( $this->config->get('socnetauth2_confirm_telephone_status') == 2 || (
			$this->config->get('socnetauth2_confirm_telephone_status') == 1 && empty($data['telephone'])
		) )
		{
			$confirm_data['telephone'] = $data['telephone'];
		}
		
		/* kin insert metka: c1 */
		if( $this->config->get('socnetauth2_confirm_company_status') == 2 || (
			$this->config->get('socnetauth2_confirm_company_status') == 1 && empty($data['company'])
		) )
		{
			$confirm_data['company'] = '';
		}
		
		if( $this->config->get('socnetauth2_confirm_address_1_status') == 2 || (
			$this->config->get('socnetauth2_confirm_address_1_status') == 1 && empty($data['address_1'])
		) )
		{
			$confirm_data['address_1'] = '';
		}
		
		if( $this->config->get('socnetauth2_confirm_postcode_status') == 2 || (
			$this->config->get('socnetauth2_confirm_postcode_status') == 1 && empty($data['postcode'])
		) )
		{
			$confirm_data['postcode'] = '';
		}
		
		if( $this->config->get('socnetauth2_confirm_city_status') == 2 || (
			$this->config->get('socnetauth2_confirm_city_status') == 1 && empty($data['city'])
		) )
		{
			$confirm_data['city'] = '';
		}
		
		if( $this->config->get('socnetauth2_confirm_zone_status') == 2 || (
			$this->config->get('socnetauth2_confirm_zone_status') == 1 && empty($data['zone'])
		) )
		{
			$confirm_data['zone'] = '';
		}
		
		if( $this->config->get('socnetauth2_confirm_country_status') == 2 || (
			$this->config->get('socnetauth2_confirm_country_status') == 1 && empty($data['country'])
		) )
		{
			$confirm_data['country'] = '';
		}
		/* end kin metka: c1 */
		
		
		if( !$confirm_data )
		{	
			return false;
		}
		else
		{		
			return $confirm_data;
		}
	}
	
	public function country() 
	{
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
?>