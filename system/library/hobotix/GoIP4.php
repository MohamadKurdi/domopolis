<?php

namespace hobotix;

class GoIP4
{
    private $config = null;
    private $login = null;
    private $password = null;
    private $server = null;
    private $timeout = 10;
    private $retries = 10;

    public function __construct($registry)
    {
        $this->config = $registry->get('config');

        $this->login = $this->config->get('config_goip4_user');
        $this->password = $this->config->get('config_goip4_passwd');
        $this->server = $this->config->get('config_goip4_uri');
    }

    private function sendUSSD($ussd, $line)
    {
        $login = $this->login;
        $password = $this->password;
        $server = $this->server;
        $timeout = $this->timeout;
        $retries = $this->retries;

        $mid = str_pad(rand(1, 10000), 8, "0", STR_PAD_LEFT);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://$login:$password@$server/default/en_US/ussd_info.html");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "line=$line&smskey=$mid&action=USSD&telnum=$ussd&send=Send");
        $out = curl_exec($curl);
        curl_close($curl);

        $done = false;
        $c = 0;

        while (!$done and $c <= $retries) {
            unset($a);
            sleep(1);
            $c += 1;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://$server/default/en_US/send_status.xml?u=$login&p=$password");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            $out = curl_exec($curl);
            curl_close($curl);

            $a = xml2array($out, 0);

            if ($a && isset($a["send-sms-status"]) && $a["send-sms-status"]["status$line"] == "DONE") {
                $done = true;
            }
        }

        if (!$done && isset($a["send-sms-status"]["error$line"])) {
            return false;
        } else {
            return $a["send-sms-status"]["error$line"];
        }
    }

    public function getKyivstarBalance($line = 1)
    {
        $ussd = urlencode("*111#");

        if ($responce = $this->sendUSSD($ussd, $line)) {
            $responce = str_replace('Na rahunku ', '', $responce);
            $responce = explode('grn.', $responce);
            $responce = (int)trim($responce[0]);
            return $responce;
        }

        return null;
    }

    public function getVodafoneBalance($line = 1)
    {
        $ussd = urlencode("*101#");

        if ($responce = $this->sendUSSD($ussd, $line)) {
            $responce = str_replace('Na Vashomu rahunku ', '', $responce);
            $responce = explode('grn.', $responce);
            $responce = (int)trim($responce[0]);

            return $responce;

        }

        return null;
    }
}