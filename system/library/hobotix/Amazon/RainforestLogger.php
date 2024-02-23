<?php

namespace hobotix\Amazon;

use Psr\Log\AbstractLogger;

class RainforestLogger extends AbstractLogger
{

	public function __construct($registry){
		$this->registry			= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');
	}

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
    	if ($level == \Psr\Log\LogLevel::ERROR){
        	echoLine('[RainforestLogger] Error: ' . $message, 'e');

        	if (strpos($message, 'Could not extract OfferResponse data from response') !== false && strpos($message, 'Rainforest response appears incomplete. It is missing field request_metadata') !== false){
        		preg_match('/(?<=~)([\s\S]+?)(?=~)/u', $message, $asin);

        		if (!empty($asin[0])){
        			echoLine('[RainforestLogger::log] We could not get info about ASIN: ' . $asin[0] . ', we suppose it is incorrect, changing it to INVALID', 'w');

        			$this->registry->get('rainforestAmazon')->offersParser->setProductNoOffers(trim($asin[0]));
        			$this->registry->get('rainforestAmazon')->infoUpdater->setInvalidASIN(trim($asin[0]));
        		}
        	}
    	}

        if ($this->config->get('config_rainforest_debug_library') && $level != \Psr\Log\LogLevel::ERROR){
            echoLine('[RainforestLogger::log] Logging ' . $level . ' message: ' . $message, 'w');            
        }
    }
}