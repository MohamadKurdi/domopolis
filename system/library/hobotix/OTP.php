<?php

namespace hobotix;

final class OTP {

    private $db     = null;
    private $config = null;

    private $smsAdaptor     = null;
    private $phoneValidator = null; 
    private $customer       = null;  

    public function __construct($registry){        
        $this->config   = $registry->get('config');
        $this->db       = $registry->get('db');

        $this->smsAdaptor       = $registry->get('smsAdaptor');
        $this->phoneValidator   = $registry->get('phoneValidator');
        $this->customer         = $registry->get('customer');
    }


    public function addOTPCode($telephone){
        

    }

    public function validateOTPCode($telephone){

    }







}