<?php
class ModelModuleBonusaffiliate extends Model {

    public function getorderpaymentbutton($affiliate_id) {
		if($affiliate_id==0)
			return false;
        $query = $this->db->query("SELECT * FROM `customer` where affiliate_paid = '".(int)$affiliate_id."'");
		if ($query->num_rows)
            return $query->row;
		return false;
    }
	
	public function getorderpayment($email) {
        $query = $this->db->query("SELECT concat(firstname, ' ' ,lastname) as name FROM `customer` where email = '".$email."'");
		if ($query->num_rows)
            return $query->row['name'];
		return false;
    }
	
	public function setorderpayment($email, $affiliate_id) {
        $query = $this->db->query("UPDATE `customer` SET affiliate_paid = '".(int)$affiliate_id."' WHERE  email = '".$email."' and customer_id = '" . (int)$this->customer->getId() . "'");
    }
	
	public function getAffiliatesValidate($date) {
        if(!$this->config->get('config_affiliate_visible_telephone')) {
			$date['telephone'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_fax')) {
			$date['fax'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_company')) {
			$date['company'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_website')) {
			$date['website'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_address_1')) {
			$date['address_1'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_address_2')) {
			$date['address_2'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_city')) {
			$date['city'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_postcode')) {
			$date['postcode'] = '';
		}
		if(!$this->config->get('config_affiliate_visible_country_id')) {
			$date['country_id'] = '0';
		}
		if(!$this->config->get('config_affiliate_visible_zone_id')) {
			$date['zone_id'] = '0';
		}
		if(!$this->config->get('config_affiliate_visible_tax')) {
			$date['tax'] = '';
		}
		if(($this->config->get('config_affiliate_visible_tracking'))
			& (isset($date['tracking']))) {
			setcookie('tracking', $date['tracking']);
			$this->request->cookie['tracking'] = $date['tracking'];
		}
		return $date;
    }
	
	public function getAffiliatesValidateError($error) {
        if(!$this->config->get('config_affiliate_visible_telephone')) {
			unset($error['telephone']);
		}
		if(!$this->config->get('config_affiliate_visible_fax')) {
			unset($error['fax']);
		}
		if(!$this->config->get('config_affiliate_visible_company')) {
			unset($error['company']);
		}
		if(!$this->config->get('config_affiliate_visible_website')) {
			unset($error['website']);
		}
		if(!$this->config->get('config_affiliate_visible_address_1')) {
			unset($error['address_1']);
		}
		if(!$this->config->get('config_affiliate_visible_address_2')) {
			unset($error['address_2']);
		}
		if(!$this->config->get('config_affiliate_visible_city')) {
			unset($error['city']);
		}
		if(!$this->config->get('config_affiliate_visible_postcode')) {
			unset($error['postcode']);
		}
		if(!$this->config->get('config_affiliate_visible_country_id')) {
			unset($error['country_id']);
		}
		if(!$this->config->get('config_affiliate_visible_zone_id')) {
			unset($error['zone_id']);
		}
		if(!$this->config->get('config_affiliate_visible_tax')) {
			unset($error['tax']);
		}
		if(!$this->config->get('config_affiliate_visible_tracking')&
			(bool)$this->config->get('config_affiliate_required_tracking')) {
			unset($error['tracking']);
		}
		return $error;
    }
	
	public function getAffiliatesAdd($data) {
		if($this->config->get('config_affiliate_login')) {
			$data['website'] = '';
			$data['payment'] = '';
			$data['cheque'] = '';
			$data['paypal'] = '';
			$data['bank_name'] = '';
			$data['bank_branch_number'] = '';
			$data['bank_swift_code'] = '';
			$data['bank_account_name'] = '';
			$data['bank_account_number'] = '';
			$data['qiwi'] = '';
			$data['card'] = '';
			$data['yandex'] = '';
			$data['webmoney'] = '';
			$data['webmoneyWMZ'] = '';
			$data['webmoneyWMU'] = '';
			$data['webmoneyWME'] = '';
			$data['webmoneyWMY'] = '';
			$data['webmoneyWMB'] = '';
			$data['webmoneyWMG'] = '';
			$data['AlertPay'] = '';
			$data['Moneybookers'] = '';
			$data['LIQPAY'] = '';
			$data['SagePay'] = '';
			$data['twoCheckout'] = '';
			$data['GoogleWallet'] = '';
			$data['tax'] = '';
			$this->load->model('affiliate/affiliate');
			$existaffiliate = $this->model_affiliate_affiliate->getAffiliateByEmail($this->db->escape($data['email']));
			if(!$existaffiliate) {
				$this->model_affiliate_affiliate->addAffiliate($data);
				$this->affiliate->login($this->db->escape($data['email']), $this->db->escape($data['password']));
			}
			$this->setorderpayment($data['email'], (int)$this->affiliate->getId());
		}
	}
}
?>