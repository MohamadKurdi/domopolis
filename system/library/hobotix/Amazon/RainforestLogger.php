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
        	echoLine('[RainforestLogger] ' . $message);

        	if (strpos($message, 'Could not extract OfferResponse data from response') !== false && strpos($message, 'Rainforest response appears incomplete. It is missing field request_metadata') !== false){
        		//try to get asin from message
        		preg_match('/(?<=~)([\s\S]+?)(?=~)/u', $message, $asin);

        		if (!empty($asin[0])){
        			echoLine('[RainforestLogger] Мы не смогли получить ничего по ASIN ' . $asin[0] . ', скорее всего он некорректный, ставим на него маркер INVALID');

        			$this->registry->get('rainforestAmazon')->offersParser->setProductNoOffers(trim($asin[0]));
        			$this->registry->get('rainforestAmazon')->infoUpdater->setInvalidASIN(trim($asin[0]));
        		}
        	}
    	}
    }
}