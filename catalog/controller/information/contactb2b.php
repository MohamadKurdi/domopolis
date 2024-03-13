<?php

class ControllerInformationContactB2b extends Controller
{

    private $error = [];

    public function index()
    {
        $this->language->load('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['text_messenger_contact'] = $this->language->get('text_messenger_contact');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $template = new EmailTemplate($this->request, $this->registry);

            $tracking = [];
            $tracking['ip_address'] = $this->request->server['REMOTE_ADDR'];
            $tracking['user_agent'] = (isset($this->request->server['HTTP_USER_AGENT'])) ? $this->request->server['HTTP_USER_AGENT'] : '';
            $tracking['accept_language'] = (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) ? $this->request->server['HTTP_ACCEPT_LANGUAGE'] : '';
            if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                $tracking['remote_host'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                $tracking['remote_host'] = $this->request->server['HTTP_CLIENT_IP'];
            } else {
                $tracking['remote_host'] = '';
            }

            $template->addData($this->request->post);

            $template->data['enquiry'] = html_entity_decode(str_replace("\n", "<br />", $this->request->post['enquiry']), ENT_QUOTES, 'UTF-8');
            $template->data['user_tracking'] = $tracking;

            $mail = new Mail($this->registry);
            $mail->setTo($this->config->get('config_email_b2b_to'));
            $mail->setFrom($this->request->post['email']);
            $mail->setSender($this->request->post['name']);
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
            $mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'], ENT_QUOTES, 'UTF-8')));
            $template->load('information.contact');
            $mail = $template->hook($mail);
            $mail->send();
            $template->sent();

            $this->data['success'] = $this->language->get('text_thanks_for_enquiry');
        }

        $this->data['breadcrumbs'] = [];

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['hb_snippets_local_enable'] = $this->config->get('hb_snippets_local_enable');
        $this->data['hb_snippets_local_snippet'] = $this->config->get('hb_snippets_local_snippet');
        $this->data['text_location'] = $this->language->get('text_location');
        $this->data['text_contact'] = $this->language->get('text_contact');
        $this->data['text_address'] = $this->language->get('text_address');
        $this->data['text_h1_title_name'] = $this->language->get('h1_title_name');
        $this->data['text_telephone'] = $this->language->get('text_telephone');
        $this->data['text_fax'] = $this->language->get('text_fax');
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');
        $this->data['button_continue'] = $this->language->get('button_continue');

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        if (isset($this->error['enquiry'])) {
            $this->data['error_enquiry'] = $this->error['enquiry'];
        } else {
            $this->data['error_enquiry'] = '';
        }

        if (isset($this->error['captcha'])) {
            $this->data['error_captcha'] = $this->error['captcha'];
        } else {
            $this->data['error_captcha'] = '';
        }

        $this->data['action'] = $this->url->link('information/contact');
        $this->data['store'] = $this->config->get('config_name');

        if (!empty($this->config->get('config_address'))) {
            $this->data['address'] = nl2br($this->config->get('config_address'));
        } else {
            $this->data['address'] = '';
        }

        $this->data['telephone'] = $this->config->get('config_telephone');
        $this->data['telephone2'] = $this->config->get('config_telephone2');
        $this->data['telephone3'] = $this->config->get('config_telephone3');
        $this->data['telephone4'] = $this->config->get('config_telephone4');
        $this->data['contact_email'] = $this->config->get('config_display_email');
        $this->data['opt_telephone'] = $this->config->get('config_opt_telephone');
        $this->data['opt_telephone2'] = $this->config->get('config_opt_telephone2');
        $this->data['opt_email'] = $this->config->get('config_opt_email');

        $this->data['fax'] = $this->config->get('config_fax');

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } else {
            $this->data['name'] = $this->customer->getFirstName();
        }

        if (isset($this->request->post['telephone'])) {
            $this->data['telephone'] = $this->request->post['telephone'];
        } else {
            $this->data['telephone'] = $this->customer->getTelephone();
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = $this->customer->getEmail();
        }

        if (isset($this->request->post['enquiry'])) {
            $this->data['enquiry'] = $this->request->post['enquiry'];
        } else {
            $this->data['enquiry'] = '';
        }

        if (isset($this->request->post['captcha'])) {
            $this->data['captcha'] = $this->request->post['captcha'];
        } else {
            $this->data['captcha'] = '';
        }


        $this->template = 'information/contactb2b.tpl';

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }


    protected function validate()
    {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->phoneValidator->validate($this->request->post['telephone'])) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
            $this->error['enquiry'] = $this->language->get('error_enquiry');
        }

        if ($this->config->get('config_google_recaptcha_contact_enable')) {
            $this->error['captcha'] = $this->language->get('error_captcha');
            if (!empty($this->request->post['g-recaptcha-response'])) {
                $reCaptcha = new \ReCaptcha\ReCaptcha($this->config->get('config_google_recaptcha_contact_secret'));
                $reCaptchaResponse = $reCaptcha->setScoreThreshold(0.5)->verify($this->request->post['g-recaptcha-response']);

                if ($reCaptchaResponse->isSuccess()) {
                    unset($this->error['captcha']);
                } else {
                    $this->error['captcha'] .= ' ' . json_encode($reCaptchaResponse->getErrorCodes());
                }
            }
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}