<?php

namespace hobotix;

final class OTP {

    private $db     = null;
    private $config = null;

    private $smsAdaptor     = null;
    private $phoneValidator = null; 
    private $customer       = null; 

    private $lifetime       = 600; 

    public function __construct($registry){        
        $this->config   = $registry->get('config');
        $this->db       = $registry->get('db');

        $this->smsAdaptor       = $registry->get('smsAdaptor');
        $this->phoneValidator   = $registry->get('phoneValidator');
        $this->customer         = $registry->get('customer');
        $this->session          = $registry->get('session');
    }

    public function sendOTPCode($telephone){
        $telephone  = $this->phoneValidator->format($telephone);
        $otpCode    = $this->setOTPCode($telephone);

        return $this->smsAdaptor->sendSMSOTP(['telephone' => $telephone], ['otp_code' => $otpCode]);
    }

    public function setOTPCode($telephone){
        $otpCode = generateRandomPin(6);
       
        $this->session->data['otp_telephone']   = $telephone;
        $this->session->data['otp_code']        = $otpCode;
        $this->session->data['otp_time']        = time();  

        return $otpCode;
    }

    public function getCurrentOTPTelephone(){
        if (!empty($this->session->data['otp_telephone'])){
            return $this->session->data['otp_telephone'];
        }

        return false;        
    }

    public function cleanOTPCode(){
        unset($this->session->data['otp_code']);
        unset($this->session->data['otp_time']);
        unset($this->session->data['otp_telephone']);
    }

    public function validateOTPCode($code){
        if (!empty($this->session->data['otp_code']) && !empty($this->session->data['otp_time'])){
            if ($this->session->data['otp_code'] == $code){
                if ((time() - $this->session->data['otp_time']) <= $this->lifetime){
                    $this->cleanOTPCode();

                    return true;                    
                }
            }
        }

        return false;
    }
}