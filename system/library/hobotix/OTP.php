<?php

namespace hobotix;

final class OTP {

    private $db         = null;
    private $config     = null;
    private $request    = null;

    private $smsAdaptor     = null;
    private $phoneValidator = null; 
    private $customer       = null; 

    private $lifetime           = 600; 
    private $resend_lifetime    = 60;
    private $max_tries          = 3;
    private $lifetime_tries     = 600;

    public const MAX_TRIES_EXCEEDED = -2;
    public const SMS_NOT_SENT       = -3;

    public function __construct($registry){        
        $this->config   = $registry->get('config');
        $this->db       = $registry->get('db');
        $this->request  = $registry->get('request');

        $this->smsAdaptor       = $registry->get('smsAdaptor');
        $this->phoneValidator   = $registry->get('phoneValidator');
        $this->customer         = $registry->get('customer');
        $this->session          = $registry->get('session');
    }

    public function sendOTPCode($telephone){
        $telephone  = $this->phoneValidator->format($telephone);        

        if ($this->validateTries() === false){
            return self::MAX_TRIES_EXCEEDED;
        }

        $otpCode    = $this->setOTPCode($telephone);            
        $senderID = $this->smsAdaptor->sendSMSOTP(['telephone' => $telephone], ['otp_code' => $otpCode]);            

        if (!$senderID){
            return self::SMS_NOT_SENT;
        } 

        return $senderID;     
    }

    public function setOTPCode($telephone){
        $this->updateTries();
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
                    $this->unsetTries();

                    return true;                    
                }
            }
        }

        return false;
    }

    public function validateTries(){
         $ncquery = $this->db->ncquery("SELECT * FROM otp_tries WHERE ip_addr = '" . $this->request->server['REMOTE_ADDR'] . "'");

         if ($ncquery->num_rows){
            if ((int)$ncquery->row['tries'] < $this->max_tries){
                return true;
            } else {
                if (((int)$ncquery->row['timestamp'] + $this->lifetime_tries) < time()){
                    $this->unsetTries();
                    return true;
                } else {
                    return false;
                }                
            }
         }

        return 0;
    }

    private function unsetTries(){
        $this->db->ncquery("DELETE FROM otp_tries WHERE ip_addr = '" . $this->request->server['REMOTE_ADDR'] . "'");
    }

    private function updateTries(){
        $this->db->ncquery("INSERT INTO otp_tries SET
            ip_addr = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
            tries = 1,
            timestamp = '" . (int)time() . "'
            ON DUPLICATE KEY UPDATE
            tries = tries + 1,
            timestamp = '" . (int)time() . "'");
    }
}