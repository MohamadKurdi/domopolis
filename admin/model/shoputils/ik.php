<?php 
/*
 * Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

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
?>