<?php
class ModelModuleReferrer extends Model {
    public function checkReferrer() {
        // Save first referrer
        if (!isset($this->request->cookie['first_referrer'])) {
            if(isset($this->request->get['referrer'])) {
                setcookie('first_referrer', $this->request->get['referrer'], time() + 3600 * 24 * 365 * 1000, '/');
            } elseif (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], 'http://'.$_SERVER['SERVER_NAME']) !== 0) {
                setcookie('first_referrer', $_SERVER['HTTP_REFERER'], time() + 3600 * 24 * 365 * 1000, '/');
            } else {
                setcookie('first_referrer', 'Direct', time() + 3600 * 24 * 365 * 1000, '/');
            }
        }

        // Save last referrer
        if (!isset($this->request->cookie['last_referrer'])) {
            if(isset($this->request->get['referrer'])) {
                setcookie('last_referrer', $this->request->get['referrer'], time() + 3600 * 24 * 1000, '/');
            } elseif (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], 'http://'.$_SERVER['SERVER_NAME']) !== 0) {
                setcookie('last_referrer', $_SERVER['HTTP_REFERER'], time() + 3600 * 24 * 1000, '/');
            } else {
                setcookie('last_referrer', 'Direct', time() + 3600 * 24 * 1000, '/');
            }
        }
    }
}
?>