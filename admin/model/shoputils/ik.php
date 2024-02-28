<?php 

class ModelShoputilsIk extends Model {
    const METHOD_CODE = 'shoputils_ik';

    public function getMethodKey() {
        return $this->getVersion() >= 152 ? 'payment_code' : 'payment_method';
    }

    public function getMethodCode($data) {
        if ($this->getVersion() >= 152) {
            return self::METHOD_CODE;
        }
        $method_code = $this->config->get('shoputils_ik_langdata');
        return $method_code[$data['language_id']]['title'];
    }

    protected function getVersion() {
        $version = explode('.', VERSION);
        $rev= isset($version[3]) ? 0.1*$version[3] : 0;
        $main = $version[0].$version[1].$version[2];
        return (int)$main + $rev;
    }
}