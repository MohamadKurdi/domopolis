<?php

class ControllerModuleCheaper extends Controller {
    private $error = array();

    public function index () {
        $this->language->load('module/callback');

        $json = array();
       
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['action'])) {
            if ($this->validate()) {
                $data = array();
                if (isset($this->request->post['name'])) {
                    $data['name'] = $this->request->post['name'];
                } else {
                    $data['name'] = '';
                }
                if (isset($this->request->post['phone'])) {
                    $data['phone'] = $this->request->post['phone'];
                } else {
                    $data['phone'] = '';
                }
                if (isset($this->request->post['comment_buyer'])) {
                    $data['comment_buyer'] = $this->request->post['comment_buyer'];
                } else {
                    $data['comment_buyer'] = '';
                }
                if (isset($this->request->post['email_buyer'])) {
                    $data['email_buyer'] = $this->request->post['email_buyer'];
                } else {
                    $data['email_buyer'] = '';
                }
				if (isset($this->request->post['product_id'])) {
                    $data['product_id'] = $this->request->post['product_id'];
                } else {
                    $data['product_id'] = '';
                }
				
                $this->load->model('module/callback');
				
				$data['is_cheaper'] = 1;
                
				$results = $this->model_module_callback->addCallback($data);
				
					$data = array(
						'type' => 'warning',
						'text' => 'Новая заявка на нашли дешевле', 
						'entity_type' => 'missedcall', 
						'entity_id'=> 0
					);
				
					$this->mAlert->insertAlertForGroup('sales', $data);
				
                //	$this->sendMail($data);
                $json['success'] = $this->language->get('ok');
            } else {
                $json['warning'] = $this->error;
            }

            return $this->response->setOutput(json_encode($json));
        }



        if ($this->customer->isLogged()) {
            $this->data['customer_name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
            $this->data['customer_telephone'] = $this->customer->getTelephone();
            $this->data['customer_email'] = $this->customer->getEmail();
        } else {
            $this->data['customer_name'] = '';
            $this->data['customer_telephone'] = '';
            $this->data['customer_email'] = '';
        }


        if ($this->config->get('config_img_phone_contact') && file_exists(DIR_IMAGE . $this->config->get('config_img_phone_contact'))) {
            $this->data['img_phone_contact'] = $server . 'image/' . $this->config->get('config_img_phone_contact');
        } else {
            $this->data['img_phone_contact'] = '';
        }
        if ($this->config->get('config_img_schedule_header') && file_exists(DIR_IMAGE . $this->config->get('config_img_schedule_header'))) {
            $this->data['img_schedule_header'] = $server . 'image/' . $this->config->get('config_img_schedule_header');
        } else {
            $this->data['img_schedule_header'] = '';
        }
        $this->data['sendthis'] = $this->language->get('sendthis');
        $this->data['comment_buyer'] = $this->language->get('comment_buyer');
        $this->data['email_buyer'] = $this->language->get('email_buyer');
        $this->data['namew'] = $this->language->get('namew');
        $this->data['phonew'] = $this->language->get('phonew');
        $this->data['button_send'] = $this->language->get('button_send');
        $this->data['cancel'] = $this->language->get('cancel');
        $this->data['footer_desc'] = $this->config->get('footer_description');

        $this->data['lang_id'] = $this->config->get('config_language_id');
        $this->data['mob'] = $this->config->get('config_telephone');
        $this->data['mob2'] = $this->config->get('config_telephone2');

        $this->data['config_email_1'] = $this->config->get('config_display_email');

        $this->data['block_name_phone'] = $this->config->get('block_name_phone');

        $worktime = explode(';', $this->config->get('config_worktime'));

        if ($this->customer->isLogged() && $this->customer->isOpt()) {
            $this->data['worktime'] = isset($worktime[1]) ? $worktime[1] : $worktime[0];

            //первый телефон
            $this->data['mob'] = $this->config->get('config_opt_telephone');
            $this->data['mob2'] = $this->config->get('config_opt_telephone2');

        } else {
            $this->data['worktime'] = $worktime[0];
            $this->data['mob'] = $this->config->get('config_telephone');
            $this->data['mob2'] = $this->config->get('config_telephone2');
        }

        $this->data['mask'] = $this->config->get('config_phonemask');
		
		$this->data['product_id'] = $this->request->get['pid'];


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cheaper.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/cheaper.tpl';
        } else {
            $this->template = 'default/template/module/cheaper.tpl';
        }


        $this->response->setOutput($this->render());
    }

    private function validate () {
        $this->language->load('module/callback');
        if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
            $this->error['name'] = $this->language->get('mister');
        }
        if ((strlen(utf8_decode($this->request->post['phone'])) < 3) || (strlen(utf8_decode($this->request->post['phone'])) > 32)) {
            $this->error['phone'] = $this->language->get('wrongnumber');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function sendMail ($data) {
        $subject = $this->language->get('subject');
        $text = $this->language->get('text_1');
        $text .= $this->language->get('subject') . ":\n";
        $text .= $this->language->get('name') . $data['name'] . "\n";
        $text .= $this->language->get('phone') . $data['phone'] . "\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
        $mail->send();

        // Send to additional alert emails
        $emails = explode(',', $this->config->get('config_alert_emails'));

        foreach ($emails as $email) {
            if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                $mail->setTo($email);
                $mail->send();
            }
        }

    }
}